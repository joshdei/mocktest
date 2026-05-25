<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\CourseMaterial;
use App\Models\MockPrice;
use App\Models\PastQuestionPdf;
use App\Models\Review;
use App\Models\StudentWallet;
use App\Models\SummaryPdf;
use App\Models\Transaction;
use App\Models\UserCashback;
use App\Models\UserSubscription;
use App\Services\BadgeService;
use App\Services\PerformanceService;
use App\Services\StreakService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    public function __construct(
        private StreakService $streakService,
        private PerformanceService $performanceService,
        private BadgeService $badgeService
    ) {}

    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('login');
    }

    public function dashboard()
    {
        $pastQuestions = PastQuestionPdf::latest()->take(8)->get();
        $summaries = SummaryPdf::latest()->take(6)->get();
        $calendar = Calendar::where('status', 'active')
            ->where('end_date', '>=', now()->toDateString())
            ->orderBy('start_date', 'asc')
            ->take(10)
            ->get();
        $nextEvent = Calendar::where('status', 'active')
            ->where('start_date', '>=', now()->toDateString())
            ->orderBy('start_date', 'asc')
            ->first();
        $recentDownloads = collect([
            ['title' => 'GST 101 Summary', 'course' => 'GST 101', 'type' => 'Summary'],
            ['title' => 'MTH 101 Past Questions', 'course' => 'MTH 101', 'type' => 'Past Qs'],
            ['title' => 'CSC 101 Materials', 'course' => 'CSC 101', 'type' => 'Materials'],
        ]);

        $this->processDailyCashback(Auth::user());

        $wallet = StudentWallet::firstOrCreate(
            ['user_id' => auth()->id()],
            ['balance' => 0]
        );

        $reviews = Review::latest()->take(5)->get();

        // Get streak data
        $streakData = $this->streakService->getStreakData(Auth::user());

        // Get performance data
        $performanceData = [
            'kpi' => $this->performanceService->getKPIData(Auth::user(), 'week'),
            'topics' => $this->performanceService->getTopicScores(Auth::user(), 'week'),
            'leaderboard' => $this->performanceService->getLeaderboard(Auth::user(), 'week'),
            'heatmap' => $this->performanceService->getWeeklyHeatmap(Auth::user()),
        ];
        $badgeData = $this->badgeService->syncForUser(
            Auth::user(),
            $streakData,
            $performanceData['leaderboard']
        );

        return view('dashboard.home', compact(
            'pastQuestions', 'summaries', 'calendar',
            'nextEvent', 'recentDownloads', 'wallet',
            'reviews', 'streakData', 'performanceData', 'badgeData'
        ));
    }

    private function processDailyCashback($user): void
    {
        try {
            $highestPlan = MockPrice::where('status', 'active')
                ->orderBy('price', 'desc')
                ->first();

            if (! $highestPlan) {
                return;
            }

            $userSubscription = UserSubscription::where('user_id', $user->id)
                ->where('plan_id', $highestPlan->id)
                ->where('status', 'active')
                ->where(function ($query) {
                    $query->whereNull('expiry_date')
                        ->orWhere('expiry_date', '>=', Carbon::now());
                })
                ->first();

            if (! $userSubscription) {
                return;
            }

            // Early bail before acquiring a transaction (performance)
            if (UserCashback::receivedToday($user->id)) {
                return;
            }

            $wallet = StudentWallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0]
            );

            DB::transaction(function () use ($user, $highestPlan, $wallet) {
                // Re-check inside transaction to prevent race conditions
                if (UserCashback::receivedToday($user->id)) {
                    return;
                }

                $wallet->increment('balance', 5);
                UserCashback::record($user->id, $highestPlan->id, 5.00, 'daily_login');
            });

            Log::info("Daily cashback of ₦5 credited to user {$user->id}");

        } catch (\Exception $e) {
            Log::error("Daily cashback error for user {$user->id}: ".$e->getMessage());
        }
    }

    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function about()
    {
        return view('about');
    }

    /* ── STORE ── */
    public function storeSummaries(Request $request)
    {
        $allItems = SummaryPdf::query()
            ->when($request->search, fn ($q, $s) => $q->where('course_code', 'like', "%{$s}%"))
            ->latest()
            ->get();

        $filtered = $allItems->filter(function ($item) {
            $path = '/home/psalmedu/public_html/public/storage/'.$item->file_path;

            return file_exists($path);
        });

        $page = request()->get('page', 1);
        $perPage = 12;
        $items = new LengthAwarePaginator(
            $filtered->forPage($page, $perPage)->values(),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('store.summaries', compact('items'));
    }

    public function storePastQuestions(Request $request)
    {
        $allItems = PastQuestionPdf::query()
            ->when($request->search, fn ($q, $s) => $q->where('course_code', 'like', "%{$s}%"))
            ->latest()
            ->get();

        $filtered = $allItems->filter(function ($item) {
            $path = '/home/psalmedu/public_html/public/storage/'.$item->file_path;

            return file_exists($path);
        });

        $page = request()->get('page', 1);
        $perPage = 12;
        $items = new LengthAwarePaginator(
            $filtered->forPage($page, $perPage)->values(),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('store.past-questions', compact('items'));
    }

    public function storeMaterials(Request $request)
    {
        $allItems = CourseMaterial::query()
            ->when($request->search, fn ($q, $s) => $q->where('course_code', 'like', "%{$s}%"))
            ->latest()
            ->get();

        // Filter to only items where the file actually exists
        $filtered = $allItems->filter(function ($item) {
            $path = '/home/psalmedu/public_html/public/storage/'.$item->file_path;

            return file_exists($path);
        });

        // Manual pagination
        $page = request()->get('page', 1);
        $perPage = 12;
        $items = new LengthAwarePaginator(
            $filtered->forPage($page, $perPage)->values(),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('store.materials', compact('items'));
    }

    /* ── STORE DOWNLOADS ── */
    public function downloadSummary($id)
    {
        $file = SummaryPdf::findOrFail($id);
        $path = '/home/psalmedu/public_html/public/storage/'.$file->file_path;

        if (! file_exists($path)) {
            return redirect()->route('store.summaries')
                ->with('error', 'Sorry, the PDF for '.strtoupper($file->course_code).' is not available on the server.');
        }

        $filename = strtoupper($file->course_code).'_Summary.pdf';

        return response()->download($path, $filename);
    }

    public function downloadPastQuestion($id)
    {
        $file = PastQuestionPdf::findOrFail($id);
        $path = '/home/psalmedu/public_html/public/storage/'.$file->file_path;

        if (! file_exists($path)) {
            return redirect()->route('store.past-questions')
                ->with('error', 'Sorry, the PDF for '.strtoupper($file->course_code).' is not available on the server.');
        }

        $filename = strtoupper($file->course_code).'_PastQuestions.pdf';

        return response()->download($path, $filename);
    }

    public function downloadMaterial($id)
    {
        $file = CourseMaterial::findOrFail($id);
        $path = '/home/psalmedu/public_html/public/storage/'.$file->file_path;

        if (! file_exists($path)) {
            return redirect()->route('store.materials')
                ->with('error', 'Sorry, the PDF for '.strtoupper($file->course_code).' is not available on the server.');
        }

        $filename = strtoupper($file->course_code).'_Materials.pdf';

        return response()->download($path, $filename);
    }

    /* ── WALLET ── */
    public function wallet()
    {
        $studentWallet = StudentWallet::firstOrCreate(
            ['user_id' => Auth::id()],
            ['balance' => 0]
        );

        // Get total funded amount
        $totalFunded = Transaction::where('user_id', Auth::id())
            ->where('type', 'credit')
            ->where('status', 'success')
            ->sum('amount');

        // Get total spent amount
        $totalSpent = Transaction::where('user_id', Auth::id())
            ->where('type', 'debit')
            ->where('status', 'success')
            ->sum('amount');

        // Get recent transactions
        $recentTx = Transaction::where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        return view('wallet.index', compact('studentWallet', 'totalFunded', 'totalSpent', 'recentTx'));
    }

    public function fundWallet(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'payment_method' => 'required|in:bank,card,ussd',
        ]);

        $studentWallet = StudentWallet::firstOrCreate(
            ['user_id' => Auth::id()],
            ['balance' => 0]
        );

        $amount = $request->amount;
        $reference = 'WLT-'.strtoupper(uniqid());

        // Create transaction record
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'amount' => $amount,
            'type' => 'credit',
            'status' => 'pending', // Changed to pending for better tracking
            'reference' => $reference,
            'payment_method' => $request->payment_method,
            'description' => 'Wallet funded via '.strtoupper($request->payment_method),
        ]);

        // Update wallet balance
        $studentWallet->balance += $amount;
        $studentWallet->save();

        // Update transaction status to success
        $transaction->status = 'success';
        $transaction->save();

        return redirect()->route('wallet')->with('success', '₦'.number_format($amount).' has been added to your wallet. Transaction ID: '.$reference);
    }

    /* ── TRANSACTIONS ── */
    public function transactions()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('wallet.transactions', compact('transactions'));
    }
}
