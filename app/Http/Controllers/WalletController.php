<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\StudentWallet;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use App\Models\UserCashback;
class WalletController extends Controller
{
public function index()
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login')->with('error', 'Please login to access your wallet.');
    }

    $studentWallet = StudentWallet::firstOrCreate(
        ['user_id' => $user->id],
        ['balance' => 0]
    );

    $totalFunded = Transaction::where('user_id', $user->id)
        ->where('type', 'credit')
        ->where('status', 'success')
        ->sum('amount') ?? 0;

    $totalSpent = Transaction::where('user_id', $user->id)
        ->where('type', 'debit')
        ->where('status', 'success')
        ->sum('amount') ?? 0;

    $recentTx = Transaction::where('user_id', $user->id)
        ->latest()
        ->take(5)
        ->get();

    $totalCashback = UserCashback::where('user_id', $user->id)
        ->sum('amount') ?? 0;

    $cashbackHistory = UserCashback::where('user_id', $user->id)
        ->with('plan')
        ->latest('cashback_date')
        ->take(5)
        ->get();

    return view('wallet.index', compact(
        'studentWallet',
        'totalFunded',
        'totalSpent',
        'recentTx',
        'totalCashback',
        'cashbackHistory'
    ));
}

    public function transactions(Request $request)
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('wallet.transactions', compact('transactions'));
    }

    public function fund(Request $request)
    {
        // Validate input
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'payment_method' => 'required|in:card,bank,ussd'
        ]);

        // Check if Paystack secret is configured
        $secretKey = config('services.paystack.secret');
        if (empty($secretKey) || $secretKey === 'sk_test_xxxxxxxxxxxxx') {
            Log::error('Paystack secret key is missing or invalid');
            return back()->with('error', 'Payment system is not properly configured. Please contact support.');
        }

        $amountInKobo = $request->amount * 100; // Convert to kobo
        $reference = 'WLT_' . strtoupper(Str::random(12));
        
        // Store pending transaction
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'reference' => $reference,
            'amount' => $request->amount,
            'type' => 'credit',
            'description' => 'Wallet funding via Paystack',
            'status' => 'pending',
        ]);

        try {
            Log::info('Initializing Paystack payment', [
                'amount' => $amountInKobo,
                'reference' => $reference,
                'email' => auth()->user()->email
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.paystack.co/transaction/initialize', [
                'email' => auth()->user()->email,
                'amount' => $amountInKobo,
                'reference' => $reference,
                'callback_url' => route('wallet.verify', $reference),
                'metadata' => [
                    'user_id' => auth()->id(),
                    'amount' => $request->amount,
                    'payment_method' => $request->payment_method,
                    'transaction_id' => $transaction->id
                ]
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if ($responseData['status'] && isset($responseData['data']['authorization_url'])) {
                    $authorizationUrl = $responseData['data']['authorization_url'];
                    
                    Log::info('Paystack initialization successful', [
                        'authorization_url' => $authorizationUrl,
                        'reference' => $reference
                    ]);
                    
                    // Redirect to Paystack payment page
                    return redirect()->away($authorizationUrl);
                } else {
                    throw new \Exception('Invalid response from Paystack: ' . json_encode($responseData));
                }
            } else {
                $errorMessage = $response->json()['message'] ?? 'Unknown error';
                Log::error('Paystack initialization failed', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                
                // Update transaction status to failed
                $transaction->update(['status' => 'failed']);
                
                return back()->with('error', 'Payment initialization failed: ' . $errorMessage);
            }
            
        } catch (\Exception $e) {
            Log::error('Exception during payment initialization: ' . $e->getMessage());
            $transaction->update(['status' => 'failed']);
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function verify($reference)
    {
        Log::info('Verifying Paystack payment', ['reference' => $reference]);

        // Find the transaction
        $transaction = Transaction::where('reference', $reference)->first();
        
        if (!$transaction) {
            Log::error('Transaction not found', ['reference' => $reference]);
            return redirect()->route('wallet')->with('error', 'Transaction not found.');
        }

        // If already successful, redirect
        if ($transaction->status === 'success') {
            return redirect()->route('wallet')->with('success', 'Payment already processed successfully.');
        }

        $secretKey = config('services.paystack.secret');
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
            ])->get("https://api.paystack.co/transaction/verify/" . $reference);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if ($responseData['status'] && $responseData['data']['status'] === 'success') {
                    $amount = $responseData['data']['amount'] / 100;
                    
                    // Update transaction
                    $transaction->update([
                        'status' => 'success',
                        'paystack_data' => json_encode($responseData['data'])
                    ]);

                    // Update wallet balance
                    $studentWallet = StudentWallet::firstOrCreate(
                        ['user_id' => $transaction->user_id],
                        ['balance' => 0]
                    );
                    $studentWallet->increment('balance', $amount);

                    Log::info('Wallet funded successfully', [
                        'user_id' => $transaction->user_id,
                        'amount' => $amount,
                        'reference' => $reference
                    ]);

                    return redirect()->route('wallet')->with('success', '✅ ₦' . number_format($amount) . ' has been added to your wallet.');
                } else {
                    $transaction->update(['status' => 'failed']);
                    return redirect()->route('wallet')->with('error', 'Payment verification failed. Payment was not successful.');
                }
            } else {
                Log::error('Paystack verification HTTP error', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                return redirect()->route('wallet')->with('error', 'Unable to verify payment. Please contact support.');
            }
            
        } catch (\Exception $e) {
            Log::error('Exception during payment verification: ' . $e->getMessage());
            return redirect()->route('wallet')->with('error', 'An error occurred during verification.');
        }
    }
}