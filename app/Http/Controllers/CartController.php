<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SummaryPdf;

class CartController extends Controller
{
    // Add item to cart via AJAX
    public function add(Request $request)
    {
        $summary = SummaryPdf::findOrFail($request->pdf_id);
        
        // Get cart from session or create new
        $cart = session()->get('cart', []);
        
        // Check if item already exists in cart
        if(isset($cart[$summary->id])) {
            $cart[$summary->id]['quantity']++;
        } else {
            $cart[$summary->id] = [
                'id' => $summary->id,
                'course_code' => $summary->course_code,
                'price' => $summary->price,
                'quantity' => 1,
                'title' => $summary->title ?? $this->getTitleFromCode($summary->course_code)
            ];
        }
        
        // Save cart to session
        session()->put('cart', $cart);
        
        // Calculate total items in cart
        $totalItems = array_sum(array_column($cart, 'quantity'));
        
        return response()->json([
            'success' => true,
            'message' => $summary->course_code . ' added to cart!',
            'cart_count' => $totalItems,
            'cart_total' => $this->getCartTotal()
        ]);
    }
    
    // Get cart total
    private function getCartTotal()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $item) {
            $total += (float)$item['price'] * $item['quantity'];
        }
        return $total;
    }
    
    // Get cart data via AJAX (for the drawer)
    public function getCart()
    {
        $cart = session()->get('cart', []);
        $totalItems = array_sum(array_column($cart, 'quantity'));
        $total = $this->getCartTotal();
        
        // Convert cart object to array of items (what frontend expects)
        $items = [];
        foreach($cart as $id => $item) {
            // Fetch the full summary data to get title if not already in cart
            if(!isset($item['title'])) {
                $summary = SummaryPdf::find($id);
                if($summary) {
                    $title = $summary->title ?? $this->getTitleFromCode($item['course_code']);
                } else {
                    $title = $this->getTitleFromCode($item['course_code']);
                }
            } else {
                $title = $item['title'];
            }
            
            $items[] = [
                'id' => $id,
                'course_code' => $item['course_code'],
                'title' => $title,
                'price' => $item['price'],
                'quantity' => $item['quantity']
            ];
        }
        
        return response()->json([
            'items' => $items,
            'total_items' => $totalItems,
            'total' => $total
        ]);
    }
    
    // View cart page
    // View cart page
public function view()
{
    // Get cart from session
    $cart = session()->get('cart', []);
    
    // Debug: Log what's in the cart
    //Log::info('Cart contents on view page:', $cart);
    
    $items = [];
    $total = 0;
    
    foreach($cart as $id => $item) {
        // Try to get the summary details
        $summary = SummaryPdf::find($id);
        
        if($summary) {
            $items[] = (object)[
                'id' => $id,
                'course_code' => $summary->course_code,
                'title' => $summary->title ?? $this->getTitleFromCode($summary->course_code),
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => (float)$item['price'] * $item['quantity']
            ];
        } else {
            // Fallback if summary not found in database
            $items[] = (object)[
                'id' => $id,
                'course_code' => $item['course_code'] ?? 'Unknown',
                'title' => $item['title'] ?? $this->getTitleFromCode($item['course_code'] ?? ''),
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => (float)$item['price'] * $item['quantity']
            ];
        }
        
        $total += (float)$item['price'] * $item['quantity'];
    }
    
    return view('pages.cart', compact('items', 'total'));
}
    
// Remove item from cart via AJAX
    public function remove(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        $removed = false;
        
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            $removed = true;
        }
        
        // Always return JSON for AJAX requests from cart page
        if($request->wantsJson() || $request->ajax() || request()->ajax()) {
            $totalItems = empty($cart) ? 0 : array_sum(array_column($cart, 'quantity'));
            $total = $this->getCartTotal();
            
            return response()->json([
                'success' => $removed,
                'message' => $removed ? 'Item removed from cart' : 'Item not found in cart',
                'cart' => [
                    'subtotal' => $total,
                    'total' => $total,
                    'item_count' => $totalItems
                ]
            ]);
        }
        
        return redirect()->url('cart/view')->with('success', 'Item removed from cart!');
    }
    
// Update quantity
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $cart = session()->get('cart', []);
        $updated = false;
        
        if(isset($cart[$id])) {
            $cart[$id]['quantity'] = (int) $request->quantity;
            if ($cart[$id]['quantity'] <= 0) {
                unset($cart[$id]);
            }
            session()->put('cart', $cart);
            $updated = true;
        }
        
        // Return JSON for AJAX requests
        if($request->wantsJson() || $request->ajax()) {
            $item = $cart[$id] ?? null;
            $totalItems = array_sum(array_column($cart, 'quantity'));
            $total = $this->getCartTotal();
            
            return response()->json([
                'success' => $updated,
                'message' => $updated ? 'Cart updated!' : 'Item not found in cart',
                'item' => $item ? [
                    'id' => $id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => (float)$item['price'] * $item['quantity']
                ] : null,
                'cart' => [
                    'subtotal' => $total,
                    'total' => $total,
                    'item_count' => $totalItems
                ]
            ]);
        }
        
        // Fallback: redirect for non-AJAX requests
        return redirect()->url('cart/view')->with('success', 'Cart updated!');
    }
    
    // Clear cart
    public function clear()
    {
        session()->forget('cart');
        
        if(request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart_count' => 0,
                'cart_total' => 0
            ]);
        }
        
        return redirect()->url('cart/view')->with('success', 'Cart cleared!');
    }
    
    // Checkout page
    // Checkout page
public function checkout()
{
  dd($this->getCartTotal());
    $cart = session()->get('cart', []);
    
    if(empty($cart)) {
        return redirect()->route('cart.view')->with('error', 'Your cart is empty!');
    }
    
    $items = [];
    $total = 0;
    
    foreach($cart as $id => $item) {
        $summary = SummaryPdf::find($id);
        
        if($summary) {
            $items[] = (object)[
                'id' => $id,
                'course_code' => $summary->course_code,
                'title' => $summary->title ?? $this->getTitleFromCode($summary->course_code),
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => (float)$item['price'] * $item['quantity']
            ];
        } else {
            $items[] = (object)[
                'id' => $id,
                'course_code' => $item['course_code'] ?? 'Unknown',
                'title' => $item['title'] ?? $this->getTitleFromCode($item['course_code'] ?? ''),
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => (float)$item['price'] * $item['quantity']
            ];
        }
        
        $total += (float)$item['price'] * $item['quantity'];
    }
    
    return view('pages.checkout', compact('items', 'total'));
}
    
    // Helper method to get title from course code
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
            'ENG' => 'English Studies',
            'PHY' => 'Physics Summary',
            'CHE' => 'Chemistry Summary',
            'POL' => 'Political Science Summary',
            'SOC' => 'Sociology Summary',
            'HIS' => 'History Summary',
            'GST' => 'General Studies',
        ];
        
        foreach ($titles as $prefix => $title) {
            if (str_contains($code, $prefix)) {
                return $title;
            }
        }
        
        return 'Study Summary';
    }

    // Process checkout
public function processCheckout(Request $request)
{
    $request->validate([
        'payment_method' => 'required|in:paystack,bank_transfer'
    ]);
    
    $cart = session()->get('cart', []);
    
    if(empty($cart)) {
        return redirect()->route('cart.view')->with('error', 'Your cart is empty!');
    }

    $user = auth()->user();
    $total = $this->getCartTotal(); // calculate once, reuse

    session()->put('order_total', $total); // ← ADD THIS

    session()->put('order_details', [
        'name'           => $user->first_name . ' ' . $user->last_name,
        'email'          => $user->email,
        'phone'          => $user->telephone,
        'payment_method' => $request->payment_method,
        'total'          => $total,
        'items'          => $cart
    ]);
    
    if($request->payment_method == 'paystack') {
        return redirect()->route('paystack.initialize');
    }
    
    return redirect()->route('bank.transfer')->with('order_details', session()->get('order_details'));
}
}