<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DraftTimetableController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\MockExamController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaystackPlanController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QotdController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\TimeTableController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\EmailVerificationController;
use Illuminate\Support\Facades\Route;

/* ── PUBLIC PAGES ── */

Route::get('/', [PageController::class, 'index'])->name('home');

Route::get('/sitemap.xml', function () {
    return response()->file(public_path('sitemap.xml'), [
        'Content-Type' => 'application/xml',
    ]);
});
Route::middleware('auth')->prefix('wallet')->group(function () {
    Route::get('/', [WalletController::class, 'index'])->name('wallet');
    Route::get('/transactions', [WalletController::class, 'transactions'])->name('transactions');
    Route::post('/fund', [WalletController::class, 'fund'])->name('wallet.fund');
    Route::get('/verify/{reference}', [WalletController::class, 'verify'])->name('wallet.verify');
});

Route::get('/about', [PageController::class, 'about'])->name('about');

/* ── AUTH PAGES (GET) ── */
Route::middleware('guest')->group(function () {
    Route::get('/login', [PageController::class, 'login'])->name('login');
    Route::get('/register', [PageController::class, 'register'])->name('register');
    Route::get('/forgot-password', [PageController::class, 'forgotPassword'])->name('password.request');

    /* ── GOOGLE AUTH (GUEST) ── */
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

/* ── AUTH ACTIONS (POST) ── */
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

// Resend verification email (custom route for this project)
Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
    ->middleware('auth')
    ->name('verification.send');

// Compatibility route: some views/flows might call the default Laravel name.
Route::get('/email/verify', function () {
    abort(404);
})->middleware('auth')->name('verification.verify');

/* ── GOOGLE AUTH ONE TAP (POST) ── */
Route::post('/auth/google/onetap', [GoogleAuthController::class, 'oneTap'])->name('auth.google.onetap');

/* ── PROTECTED DASHBOARD ── */
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');

    // Question of the Day
    Route::get('/dashboard/qotd/current', [QotdController::class, 'current'])->name('qotd.current');
    Route::post('/dashboard/qotd/submit', [QotdController::class, 'submit'])->name('qotd.submit');

    Route::post('/compliant', [ComplaintController::class, 'compliant'])->name('compliant');
    Route::post('/reviews', [ReviewsController::class, 'store'])->name('reviews.store');
    Route::get('/paystack/initialize', [PaymentController::class, 'payWithPaystack'])->name('paystack.initialize');
    Route::get('/paystack/callback', [PaymentController::class, 'handleCallback'])->name('paystack.callback');
    Route::get('/payment/success/{reference}', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/download/{id}', [PaymentController::class, 'downloadPdf'])->name('payment.download');
    Route::get('/bank-transfer', [PaymentController::class, 'bankTransfer'])->name('bank.transfer');

    /* ── STORE ── */
    Route::get('/store/summaries', [PageController::class, 'storeSummaries'])->name('store.summaries');
    Route::get('/store/past-questions', [PageController::class, 'storePastQuestions'])->name('store.past-questions');
    Route::get('/store/materials', [PageController::class, 'storeMaterials'])->name('store.materials');
    Route::get('/store/timetable', [TimeTableController::class, 'index'])->name('store.timetable');

    /* ── STORE DOWNLOADS ── */
    Route::get('/store/summaries/{id}/download', [PageController::class, 'downloadSummary'])->name('store.summaries.download');
    Route::get('/store/past-questions/{id}/download', [PageController::class, 'downloadPastQuestion'])->name('store.past-questions.download');
    Route::get('/store/materials/{id}/download', [PageController::class, 'downloadMaterial'])->name('store.materials.download');

    /* ── PROFILE ── */
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    /* ── GOOGLE AUTH (AUTHENTICATED) ── */
    Route::get('/auth/google/connect', [GoogleAuthController::class, 'connect'])->name('auth.google.connect');
    Route::get('/auth/google/connect/callback', [GoogleAuthController::class, 'connectCallback'])->name('auth.google.connect.callback');
    Route::delete('/auth/google/disconnect', [GoogleAuthController::class, 'disconnect'])->name('auth.google.disconnect');

    /* ── CART ── */
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'getCart'])->middleware('auth');
        Route::post('/add', [CartController::class, 'add'])->middleware('auth');
        Route::delete('/remove/{id}', [CartController::class, 'remove'])->middleware('auth');
        Route::post('/update/{id}', [CartController::class, 'update'])->middleware('auth');
        Route::get('/view', [CartController::class, 'view'])->middleware('auth')->name('view');
        Route::get('/checkout', [CartController::class, 'checkout'])->middleware('auth')->name('checkout'); // ← ADD THIS
        Route::post('/checkout', [CartController::class, 'processCheckout'])->middleware('auth')->name('process-checkout');

    });

    Route::prefix('timetable')->name('timetable.')->group(function () {
        Route::post('/generate', [TimeTableController::class, 'generate'])->name('generate');
        Route::get('/suggest', [TimeTableController::class, 'suggestCourses'])->name('suggest');
        Route::get('/courses', [TimeTableController::class, 'getAllCourses'])->name('courses');
        Route::post('/save', [TimeTableController::class, 'save'])->name('save');
        Route::get('/load', [TimeTableController::class, 'load'])->name('load');
        Route::delete('/delete/{id}', [TimeTableController::class, 'delete'])->name('delete');
        // for draft
        Route::post('/draft', [TimeTableController::class, 'saveDraft'])->name('draft');
        Route::get('/draft/load', [TimeTableController::class, 'loadDraft'])->name('draft.load');
        Route::delete('/draft/delete/{id}', [TimeTableController::class, 'deleteDraft'])->name('draft.delete');
    });

    Route::prefix('draft')->name('draft.')->group(function () {
        Route::get('/draft', [DraftTimetableController::class, 'index'])->name('index');
        Route::post('/generate', [DraftTimetableController::class, 'generate'])->name('generate');
        Route::get('/suggest', [DraftTimetableController::class, 'suggestCourses'])->name('suggest');
        Route::get('/courses', [DraftTimetableController::class, 'getAllCourses'])->name('courses');
        Route::post('/save', [DraftTimetableController::class, 'save'])->name('save');
        Route::get('/load', [DraftTimetableController::class, 'load'])->name('load');
        Route::delete('/delete/{id}', [DraftTimetableController::class, 'delete'])->name('delete');
    });

    Route::prefix('mock')->name('mock.')->group(function () {
        Route::get('/', [MockExamController::class, 'index'])->name('index');
        Route::get('/setup/{courseId}', [MockExamController::class, 'setup'])->name('setup');
        Route::post('/charge/{courseId}/{planId}', [MockExamController::class, 'charge'])->name('charge');
        Route::get('/setup2/{courseId}', [MockExamController::class, 'setup2'])->name('setup2');
        Route::post('/start/{courseId}', [MockExamController::class, 'start'])->name('start');
        Route::post('/submit', [MockExamController::class, 'submit'])->name('submit');
        Route::get('/result', [MockExamController::class, 'result'])->name('result');
        Route::get('/review', [MockExamController::class, 'review'])->name('review');
        Route::get('/results', [MockExamController::class, 'results'])->name('results');
        Route::post('/ai-explain', [MockExamController::class, 'aiExplanation'])->name('ai-explain');
    });

    /* ── STUDY CHALLENGES ── */
    Route::post('/challenge/find-opponent', [\App\Http\Controllers\ChallengeController::class, 'findOpponent'])->name('challenge.find-opponent');
    Route::post('/challenge/send', [\App\Http\Controllers\ChallengeController::class, 'sendChallenge'])->name('challenge.send');
    Route::post('/challenge/{challenge}/challenger-submit', [\App\Http\Controllers\ChallengeController::class, 'challengerSubmit'])->name('challenge.challenger-submit');
    Route::get('/challenge/{challenge}/play', [\App\Http\Controllers\ChallengeController::class, 'play'])->name('challenge.play');
    Route::post('/challenge/{challenge}/opponent-submit', [\App\Http\Controllers\ChallengeController::class, 'opponentSubmit'])->name('challenge.opponent-submit');

    /* ── PLAN ROUTES ── */

    Route::prefix('plan')->name('plan.')->group(function () {
        Route::get('/', [PlanController::class, 'index'])->name('index');
        Route::post('/subscribe', [PlanController::class, 'subscribe'])->name('subscribe');
        Route::post('/upgrade', [PlanController::class, 'upgrade'])->name('upgrade');
    });

    /* ── PAYSTACK PLAN PAYMENT ROUTES ── */
    Route::prefix('paystack/plan')->name('paystack.plan.')->group(function () {
        Route::post('/initialize', [PaystackPlanController::class, 'initialize'])->name('initialize');
        Route::get('/callback', [PaystackPlanController::class, 'handleCallback'])->name('callback');
        Route::get('/cancel', [PaystackPlanController::class, 'cancel'])->name('cancel');
    });
});
