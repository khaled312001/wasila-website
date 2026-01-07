<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->redirectUrl(config('services.google.redirect'))
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $customer = Customer::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($customer) {
                // Update existing customer
                $customer->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'email_verified_at' => $customer->email_verified_at ?? now(),
                ]);
            } else {
                // Create new customer
                $customer = Customer::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'email_verified_at' => now(),
                    'locale' => app()->getLocale(),
                ]);
            }

            // Login the customer
            Auth::guard('customer')->login($customer, true);
            
            // Regenerate session to prevent session fixation attacks and ensure session is saved
            request()->session()->regenerate();
            
            // Force save session to ensure it's persisted
            request()->session()->save();

            // Check if there's a checkout redirect
            if (session()->has('checkout_redirect')) {
                $redirectUrl = session('checkout_redirect');
                session()->forget('checkout_redirect');
                return redirect($redirectUrl)
                    ->with('success', __('messages.login_successful'));
            }

            // If user came from login page, redirect to dashboard
            // Otherwise redirect to home (but ensure they're logged in)
            $intendedUrl = session()->pull('url.intended');
            
            // Default redirect to home page (not dashboard) to show login status in navbar
            $redirectTo = $intendedUrl ?: route('home');
            
            return redirect($redirectTo)
                ->with('success', __('messages.login_successful'));

        } catch (\Exception $e) {
            \Log::error('Google login error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('customer.login')
                ->with('error', __('messages.login_failed') . ': ' . $e->getMessage());
        }
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('home')
            ->with('success', __('messages.logout_successful'));
    }
}

