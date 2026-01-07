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

            // Login the customer with remember me
            Auth::guard('customer')->login($customer, true);
            
            // Regenerate session AFTER login to prevent session fixation attacks
            // This will maintain the authentication state
            request()->session()->regenerate(true);
            
            // Re-login after regeneration to ensure auth state is maintained
            Auth::guard('customer')->login($customer, true);
            
            // Verify login was successful
            if (!Auth::guard('customer')->check()) {
                \Log::error('Login failed after session regeneration', [
                    'customer_id' => $customer->id,
                    'customer_email' => $customer->email
                ]);
                throw new \Exception('Failed to maintain login after session regeneration');
            }
            
            // Log successful login for debugging
            \Log::info('Google login successful', [
                'customer_id' => $customer->id,
                'customer_email' => $customer->email,
                'session_id' => request()->session()->getId()
            ]);

            // Check if there's a checkout redirect
            if (session()->has('checkout_redirect')) {
                $redirectUrl = session('checkout_redirect');
                session()->forget('checkout_redirect');
                return redirect($redirectUrl)
                    ->with('success', __('messages.login_successful'));
            }

            // Always redirect to home page to show login status in navbar
            return redirect()->route('home')
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

