<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SummaryPdf;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // This handles the GET request from CartController after checkout
    public function payWithPaystack()
    {
        $orderDetails = session()->get('order_details');
        
        if(!$orderDetails) {
            return redirect()->url('cart/checkout')->with('error', 'Please fill in your details first.');
        }
        
        $cart = $orderDetails['items'];
        $total = $orderDetails['total'];
        
        // Generate a unique reference
        $reference = $this->generateReference();
        
        // Store reference with order details
        $orderDetails['reference'] = $reference;
        session()->put('order_details', $orderDetails);
        
        // Initialize Paystack transaction
        $curl = curl_init();
        
        $callback_url = route('paystack.callback');
        $amount = $total * 100; // Convert to kobo/cents
        
        $postData = http_build_query([
            'email' => $orderDetails['email'],
            'amount' => $amount,
            'reference' => $reference,
            'callback_url' => $callback_url,
            'metadata' => json_encode([
                'name' => $orderDetails['name'],
                'phone' => $orderDetails['phone'],
                'cart_items' => $cart
            ])
        ]);
        
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . env('PAYSTACK_SECRET_KEY'),
                "Content-Type: application/x-www-form-urlencoded",
                "Cache-Control: no-cache",
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
            Log::error("Paystack Error: " . $err);
            return redirect()->url('cart/checkout')->with('error', 'Payment initialization failed. Please try again.');
        }
        
        $result = json_decode($response);
        
        if ($result->status) {
            // Redirect to Paystack payment page
            return redirect($result->data->authorization_url);
        } else {
            return redirect()->url('cart/checkout')->with('error', $result->message);
        }
    }
    
    // Handle Paystack callback
    public function handleCallback(Request $request)
    {
        $reference = $request->reference;
        
        if (!$reference) {
            return redirect()->url('cart/view')->with('error', 'Invalid payment reference');
        }
        
        // Verify transaction
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . env('PAYSTACK_SECRET_KEY'),
                "Cache-Control: no-cache",
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
            Log::error("Paystack Verification Error: " . $err);
            return redirect()->url('cart/view')->with('error', 'Payment verification failed');
        }
        
        $result = json_decode($response);
        
        if ($result->status && $result->data->status == 'success') {
            // Payment successful
            $orderDetails = session()->get('order_details');
            
            // Save order to database
            $order = $this->saveOrder($orderDetails, $result->data);
            
            // Clear cart and order details
            session()->forget('cart');
            session()->forget('order_details');
            
            // Generate download links for purchased PDFs
            $downloadLinks = $this->generateDownloadLinks($orderDetails['items']);
            
            // Send email with download links
            $this->sendPurchaseEmail($orderDetails['email'], $orderDetails['name'], $downloadLinks, $order);
            
            // Store download links in session for immediate download
            session()->put('purchased_items', $downloadLinks);
            
            return redirect()->route('payment.success', ['reference' => $reference]);
        } else {
            return redirect()->url('cart/view')->with('error', 'Payment verification failed. Please contact support.');
        }
    }
    
    // Payment success page
    public function paymentSuccess($reference)
    {
        $purchasedItems = session()->get('purchased_items', []);
        
        return view('pages.payment.success', compact('purchasedItems', 'reference'));
    }
    
    // Download PDF after payment
    public function downloadPdf($id)
    {
        $purchasedItems = session()->get('purchased_items', []);
        
        // Check if user has purchased this PDF
        if (!isset($purchasedItems[$id])) {
            abort(403, 'You have not purchased this PDF');
        }
        
        $pdf = SummaryPdf::find($id);
        
        if (!$pdf || !$pdf->file_path) {
            abort(404, 'PDF not found');
        }
        
        $filePath = storage_path('app/public/' . $pdf->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }
        
        return response()->download($filePath, $pdf->course_code . '.pdf', [
            'Content-Type' => 'application/pdf'
        ]);
    }
    
    // Bank transfer method (optional)
    public function bankTransfer()
    {
        $orderDetails = session()->get('order_details');
        
        if(!$orderDetails) {
            return redirect()->url('cart/checkout')->with('error', 'Please fill in your details first.');
        }
        
        $bankDetails = [
            'bank_name' => 'Example Bank',
            'account_name' => 'Scholar Vault Nigeria',
            'account_number' => '1234567890',
            'amount' => $orderDetails['total']
        ];
        
        return view('pages.payment.transfer', compact('bankDetails', 'orderDetails'));
    }
    
    // Save order to database
    private function saveOrder($orderDetails, $paymentData)
    {
        $order = Order::create([
            'order_reference' => $orderDetails['reference'],
            'customer_name' => $orderDetails['name'],
            'customer_email' => $orderDetails['email'],
            'customer_phone' => $orderDetails['phone'],
            'total_amount' => $orderDetails['total'],
            'payment_status' => 'completed',
            'payment_reference' => $paymentData->reference,
            'payment_data' => json_encode($paymentData)
        ]);
        
        // Save order items
        foreach ($orderDetails['items'] as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'summary_pdf_id' => $id,
                'course_code' => $item['course_code'],
                'price' => $item['price'],
                'quantity' => $item['quantity']
            ]);
        }
        
        return $order;
    }
    
    // Generate download links
    private function generateDownloadLinks($items)
    {
        $links = [];
        foreach ($items as $id => $item) {
            $links[$id] = [
                'id' => $id,
                'course_code' => $item['course_code'],
                'title' => $item['title'] ?? $this->getTitleFromCode($item['course_code']),
                'download_url' => route('payment.download', $id)
            ];
        }
        return $links;
    }
    
    // Send email with download links
    private function sendPurchaseEmail($email, $name, $downloadLinks, $order)
    {
        try {
            // You'll need to create an email view for this
            Mail::send('emails.purchase-confirmation', [
                'name' => $name,
                'order' => $order,
                'downloadLinks' => $downloadLinks
            ], function ($message) use ($email, $name) {
                $message->to($email, $name)
                        ->subject('Your PDF Downloads - ' . config('app.name'));
            });
        } catch (\Exception $e) {
            Log::error('Failed to send purchase email: ' . $e->getMessage());
        }
    }
    
    private function generateReference()
    {
        return 'NOUN_' . time() . '_' . uniqid();
    }
    
    private function getTitleFromCode($code)
    {
        $titles = [
            'MTH' => 'Introduction to Mathematics',
            'LAW' => 'Constitutional Law Summary',
            'BIO' => 'Cell Biology & Genetics',
            'BUS' => 'Entrepreneurship & SMEs',
            'CIT' => 'Introduction to Computer Science',
            'MAC' => 'Media Theory & Practice',
            'ECO' => 'Economics Summary',
            'ACC' => 'Accounting Summary',
            'PSY' => 'Psychology Summary',
        ];
        
        foreach ($titles as $prefix => $title) {
            if (str_contains($code, $prefix)) {
                return $title;
            }
        }
        
        return 'Study Summary';
    }
}