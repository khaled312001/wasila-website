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
        return Socialite::driver('google')->redirect();
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

            Auth::guard('customer')->login($customer, true);

            // Check if there's a checkout redirect
            if (session()->has('checkout_redirect')) {
                $redirectUrl = session('checkout_redirect');
                session()->forget('checkout_redirect');
                return redirect($redirectUrl)
                    ->with('success', __('messages.login_successful'));
            }

            return redirect()->route('customer.dashboard')
                ->with('success', __('messages.login_successful'));

        } catch (\Exception $e) {
            return redirect()->route('home')
                ->with('error', __('messages.login_failed'));
        }
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('home')
            ->with('success', __('messages.logout_successful'));
    }
}

