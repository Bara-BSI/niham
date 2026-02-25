<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                Auth::login($user);
                return redirect()->intended(route('dashboard', absolute: false));
            }

            return redirect()->route('login')->withErrors([
                'email' => 'Your Google Account (' . $googleUser->getEmail() . ') is not registered in our system. Please contact an administrator to create an account.',
            ]);
            
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Unable to connect to Google at this time.',
            ]);
        }
    }
}
