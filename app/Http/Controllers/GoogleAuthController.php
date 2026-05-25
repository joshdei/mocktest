<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Google_Client;
class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google
     */
    public function redirect()
    {
        try {
            Log::channel('stack')->info('========== GOOGLE AUTH START ==========');
            Log::info('Step 1: Starting Google Redirect', [
                'timestamp' => now()->toDateTimeString(),
                'session_id' => session()->getId(),
                'ip' => request()->ip()
            ]);
            
            return Socialite::driver('google')
                ->stateless()
                ->redirect();
        } catch (\Exception $e) {
            Log::error('Redirect Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString()
            ]);
            
            return redirect()->route('login')->with('error', 'Google authentication failed: ' . $e->getMessage());
        }
    }

    /**
     * Google login / register callback
     */
    public function callback()
    {
        try {
            Log::info('========== GOOGLE CALLBACK RECEIVED ==========');
            Log::info('Callback parameters', [
                'get_params' => $_GET,
                'post_params' => $_POST,
                'url' => request()->fullUrl(),
                'timestamp' => now()->toDateTimeString()
            ]);

            // Get Google user
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

            Log::info('Google User Retrieved Successfully', [
                'google_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'avatar' => $googleUser->getAvatar(),
                'token_exists' => !empty($googleUser->token),
                'timestamp' => now()->toDateTimeString()
            ]);

            // Check social account
            $socialAccount = SocialAccount::where('provider', 'google')
                ->where('provider_id', $googleUser->getId())
                ->first();

            if ($socialAccount) {
                Log::info('Existing Social Account Found', [
                    'social_account_id' => $socialAccount->id,
                    'user_id' => $socialAccount->user_id,
                    'action' => 'Logging in existing user'
                ]);
                
                Auth::login($socialAccount->user, true);
                Log::info('User logged in successfully via social account', ['user_id' => $socialAccount->user_id]);
                return redirect()->route('dashboard');
            }

            // Check existing email user
            $user = User::where('email', $googleUser->getEmail())->first();
            
            Log::info('Checking existing user by email', [
                'email' => $googleUser->getEmail(),
                'user_found' => $user ? 'Yes' : 'No',
                'user_id' => $user ? $user->id : null
            ]);

            if ($user) {
                Log::info('User Found - Creating Social Account Link', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'google_id' => $googleUser->getId()
                ]);

                try {
                    $socialLink = $user->socialAccounts()->create([
                        'provider' => 'google',
                        'provider_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'token' => $googleUser->token,
                    ]);
                    
                    Log::info('Social Account Created Successfully', ['social_account' => $socialLink->toArray()]);
                    
                    Auth::login($user, true);
                    Log::info('User logged in after linking account', ['user_id' => $user->id]);
                    return redirect()->route('dashboard');
                    
                } catch (\Exception $e) {
                    Log::error('Failed to create social account for existing user', [
                        'error' => $e->getMessage(),
                        'user_id' => $user->id,
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            }

            // Create new user
            $nameParts = $this->splitName($googleUser->getName() ?? '');
            
            Log::info('Creating New User', [
                'name_parts' => $nameParts,
                'email' => $googleUser->getEmail(),
                'avatar' => $googleUser->getAvatar()
            ]);

            // Check what columns exist in users table
            try {
                $columns = \DB::getSchemaBuilder()->getColumnListing('users');
                Log::info('Users table columns', ['columns' => $columns]);
            } catch (\Exception $e) {
                Log::warning('Could not fetch users table columns', ['error' => $e->getMessage()]);
            }

            $userData = [
                'first_name' => $nameParts['first_name'],
                'last_name'  => $nameParts['last_name'],
                'email'      => $googleUser->getEmail(),
                'avatar'     => $googleUser->getAvatar(),
                'password'   => bcrypt(Str::random(24)),
            ];
            
            // Add optional fields only if they exist in the database
            try {
                $columns = \DB::getSchemaBuilder()->getColumnListing('users');
                if (in_array('checkbox', $columns)) {
                    $userData['checkbox'] = 'off';
                    Log::info('Adding checkbox field');
                }
                if (in_array('telephone', $columns)) {
                    $userData['telephone'] = null;
                    Log::info('Adding telephone field');
                }
            } catch (\Exception $e) {
                Log::warning('Error checking columns', ['error' => $e->getMessage()]);
            }

            Log::info('Attempting to create user with data', ['user_data' => array_keys($userData)]);

            $newUser = User::create($userData);

            Log::info('New User Created Successfully', [
                'user_id' => $newUser->id,
                'email' => $newUser->email,
                'user_data' => $newUser->toArray()
            ]);

            // Create social account
            try {
                $socialLink = $newUser->socialAccounts()->create([
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'token' => $googleUser->token,
                ]);
                
                Log::info('Social Account Linked for New User', [
                    'user_id' => $newUser->id,
                    'social_account_id' => $socialLink->id
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to create social account for new user', [
                    'error' => $e->getMessage(),
                    'user_id' => $newUser->id,
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

            Auth::login($newUser, true);
            
            Log::info('New User Logged In Successfully', [
                'user_id' => $newUser->id,
                'email' => $newUser->email,
                'redirect_to' => route('dashboard')
            ]);
            
            Log::info('========== GOOGLE AUTH COMPLETED SUCCESSFULLY ==========');

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            Log::error('========== GOOGLE AUTH FAILED ==========');
            Log::error('Exception Details', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            // Check if it's a database error
            if (strpos($e->getMessage(), 'SQLSTATE') !== false) {
                Log::error('Database Error Detected', [
                    'sql_error' => $e->getMessage(),
                    'hint' => 'Check your database schema and migrations'
                ]);
            }
            
            // Check if it's a Google OAuth error
            if (strpos($e->getMessage(), 'Google') !== false || strpos($e->getMessage(), 'OAuth') !== false) {
                Log::error('Google OAuth Error Detected', [
                    'hint' => 'Check your GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in .env file',
                    'redirect_uri' => env('GOOGLE_REDIRECT_URI')
                ]);
            }
            
            Log::error('========== END ERROR LOG ==========');
            
            return redirect()->route('login')->with('error', 'Authentication failed. Please check logs for details.');
        }
    }

    /**
     * Connect Google account
     */
    public function connect()
    {
        try {
            Log::info('Starting Google Connect Process', [
                'user_logged_in' => Auth::check(),
                'user_id' => Auth::id(),
                'timestamp' => now()->toDateTimeString()
            ]);
            
            return Socialite::driver('google')
                ->stateless()
                ->redirect();
        } catch (\Exception $e) {
            Log::error('Connect Redirect Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('profile')->with('error', 'Failed to connect Google: ' . $e->getMessage());
        }
    }

    /**
     * Connect callback
     */
    public function connectCallback()
    {
        try {
            Log::info('Connect Callback Received', [
                'query_params' => $_GET,
                'user_logged_in' => Auth::check(),
                'current_user_id' => Auth::id(),
                'timestamp' => now()->toDateTimeString()
            ]);

            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

            Log::info('Google User Retrieved in Connect', [
                'google_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName()
            ]);

            $user = Auth::user();
            
            if (!$user) {
                Log::error('No User Authenticated During Connect', [
                    'google_id' => $googleUser->getId(),
                    'email' => $googleUser->getEmail()
                ]);
                
                return redirect()->route('login')->with('error', 'Please login first to connect Google account.');
            }

            $existing = SocialAccount::where('provider', 'google')
                ->where('provider_id', $googleUser->getId())
                ->first();

            if ($existing && $existing->user_id !== $user->id) {
                Log::warning('Google Account Already Linked to Another User', [
                    'existing_user_id' => $existing->user_id,
                    'current_user_id' => $user->id,
                    'google_id' => $googleUser->getId()
                ]);
                
                return redirect()->route('profile')
                    ->with('error', 'Google account already linked to another user.');
            }

            if (!$existing) {
                $socialAccount = $user->socialAccounts()->create([
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'token' => $googleUser->token,
                ]);
                
                Log::info('Google Account Connected Successfully', [
                    'user_id' => $user->id,
                    'social_account_id' => $socialAccount->id,
                    'google_id' => $googleUser->getId()
                ]);
            } else {
                Log::info('Google Account Already Connected', ['user_id' => $user->id]);
            }

            return redirect()->route('profile')
                ->with('success', 'Google connected successfully');

        } catch (\Exception $e) {
            Log::error('Connect Callback Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString()
            ]);
            
            return redirect()->route('profile')
                ->with('error', 'Failed to connect Google: ' . $e->getMessage());
        }
    }

    /**
     * Disconnect Google
     */
    public function disconnect()
    {
        try {
            $user = Auth::user();
            
            Log::info('Disconnecting Google Account', [
                'user_logged_in' => Auth::check(),
                'user_id' => $user ? $user->id : null,
                'timestamp' => now()->toDateTimeString()
            ]);

            if (!$user) {
                Log::warning('Attempt to disconnect Google while not logged in');
                return redirect()->route('login')->with('error', 'Please login first.');
            }

            $deleted = $user->socialAccounts()
                ->where('provider', 'google')
                ->delete();

            Log::info('Google Disconnected', [
                'user_id' => $user->id,
                'records_deleted' => $deleted
            ]);

            return redirect()
                ->route('profile')
                ->with('success', 'Google disconnected successfully');

        } catch (\Exception $e) {
            Log::error('Disconnect Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->route('profile')
                ->with('error', 'Failed to disconnect Google: ' . $e->getMessage());
        }
    }

    /**
     * Split name helper
     */
    private function splitName(string $name): array
    {
        $parts = explode(' ', trim($name), 2);

        return [
            'first_name' => $parts[0] ?? '',
            'last_name'  => $parts[1] ?? '',
        ];
    }


    /**
 * Google One Tap Login
 */
public function oneTap(Request $request)
{
    try {

        Log::info('========== GOOGLE ONE TAP START ==========');

        $request->validate([
            'credential' => 'required'
        ]);

        $client = new Google_Client([
            'client_id' => env('GOOGLE_CLIENT_ID')
        ]);

        $payload = $client->verifyIdToken($request->credential);

        if (!$payload) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Google token'
            ], 401);
        }

        $googleId = $payload['sub'];
        $email = $payload['email'];
        $name = $payload['name'] ?? '';
        $avatar = $payload['picture'] ?? null;

        Log::info('Google One Tap Payload', [
            'google_id' => $googleId,
            'email' => $email,
        ]);

        // Check social account
        $socialAccount = SocialAccount::where('provider', 'google')
            ->where('provider_id', $googleId)
            ->first();

        if ($socialAccount) {

            Auth::login($socialAccount->user, true);

            return response()->json([
                'success' => true,
                'redirect' => route('dashboard')
            ]);
        }

        // Check existing user
        $user = User::where('email', $email)->first();

        if (!$user) {

            $nameParts = $this->splitName($name);

            $user = User::create([
                'first_name' => $nameParts['first_name'],
                'last_name' => $nameParts['last_name'],
                'email' => $email,
                'avatar' => $avatar,
                'password' => bcrypt(Str::random(24)),
            ]);
        }

        // Create social account if not exists
        $existingSocial = SocialAccount::where('provider', 'google')
            ->where('user_id', $user->id)
            ->first();

        if (!$existingSocial) {

            $user->socialAccounts()->create([
                'provider' => 'google',
                'provider_id' => $googleId,
                'avatar' => $avatar,
            ]);
        }

        Auth::login($user, true);

        return response()->json([
            'success' => true,
            'redirect' => route('dashboard')
        ]);

    } catch (\Exception $e) {

        Log::error('Google One Tap Error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Authentication failed'
        ], 500);
    }
}
}