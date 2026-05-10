<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToGoogle()
    {
        // On local environments, cURL sometimes fails due to missing SSL certs
        if (app()->environment('local')) {
            return Socialite::driver('google')
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->redirect();
        }
        
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            // On local environments, cURL sometimes fails due to missing SSL certs
            if (app()->environment('local')) {
                $googleUser = Socialite::driver('google')
                    ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                    ->user();
            } else {
                $googleUser = Socialite::driver('google')->user();
            }
            
            // Check if a user with this google_id already exists
            $user = User::where('google_id', $googleUser->id)->first();
            
            if (!$user) {
                // Check if a user with this email exists but without google_id
                $user = User::where('email', $googleUser->email)->first();
                
                if ($user) {
                    // Update existing user with google_id
                    $user->update([
                        'google_id' => $googleUser->id,
                        // Update avatar if they don't have one
                        'avatar' => $user->avatar ?: $googleUser->avatar,
                    ]);
                } else {
                    // Create a new user
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                        'password' => Hash::make(Str::random(24)), // Random password for security
                        'email_verified_at' => now(),
                    ]);
                }
            }

            Auth::login($user);

            // Award 'Digital Twin' badge if not already earned
            app(\App\Services\GamificationService::class)->checkBadges($user);

            // Check if user has completed onboarding (monthly_income check)
            if (!$user->monthly_income) {
                return redirect()->route('onboarding')->with('success', 'Welcome! Let\'s set up your financial profile.');
            }

            return redirect()->route('dashboard')->with('success', 'Signed in with Google. Welcome back, ' . $user->name . '!');

        } catch (Exception $e) {
            \Log::error('Google Auth Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Unable to sign in with Google. Please try again.');
        }
    }
}
