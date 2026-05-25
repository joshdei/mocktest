<?php

use App\Http\Middleware\LogUserLogin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            LogUserLogin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
    $exceptions->reportable(function (Throwable $e) {
        \Illuminate\Support\Facades\Log::info('Reporter fired: ' . get_class($e));

        if (app()->environment('production')) {
            try {
                \Illuminate\Support\Facades\Mail::raw(
                    "Error: " . $e->getMessage() . "\n\n" .
                    "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n" .
                    "URL: " . request()->fullUrl() . "\n\n" .
                    "Stack Trace:\n" . $e->getTraceAsString(),
                    function ($message) {
                        $message->to(config('mail.admin_email'))
                                ->subject('🚨 PsalmEdu Error - ' . now()->format('Y-m-d H:i'));
                    }
                );
            } catch (\Throwable $mailException) {
                \Illuminate\Support\Facades\Log::error('Mail failed: ' . $mailException->getMessage());
            }
        }

        return false; // let Laravel still log it normally
    });
})->create();