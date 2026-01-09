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
        // If already logged in, redirect to home
        if (Auth::guard('customer')->check()) {
            return redirect()->route('home')
                ->with('info', __('messages.already_logged_in'));
        }
        
        return Socialite::driver('google')
            ->redirectUrl(config('services.google.redirect'))
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Check for OAuth errors first
            if (request()->has('error')) {
                $error = request()->get('error');
                \Log::error('Google OAuth error: ' . $error);
                return redirect()->route('home')
                    ->with('error', __('messages.login_failed') . ': ' . $error);
            }
            
            $googleUser = Socialite::driver('google')->user();
            
            if (!$googleUser || !$googleUser->email) {
                throw new \Exception('Failed to retrieve user information from Google');
            }
            
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
            
            // Regenerate session to prevent session fixation attacks
            // This should be done after login but before redirect
            request()->session()->regenerate();
            
            // Verify login was successful after regeneration
            if (!Auth::guard('customer')->check()) {
                \Log::error('Login failed after session regenerate', [
                    'customer_id' => $customer->id,
                    'customer_email' => $customer->email,
                    'session_id' => request()->session()->getId()
                ]);
                // Re-login if auth was lost during regeneration
                Auth::guard('customer')->login($customer, true);
            }
            
            // Ensure session is saved and committed before redirect
            // Use getSession() to get the session instance and save it
            $session = request()->session();
            $session->save();
            
            // Final verification
            if (!Auth::guard('customer')->check()) {
                \Log::error('Login lost after save', [
                    'customer_id' => $customer->id,
                    'customer_email' => $customer->email,
                    'session_id' => $session->getId()
                ]);
                // Try one more time to login and save
                Auth::guard('customer')->login($customer, true);
                $session->save();
            }
            
            // Log successful login for debugging
            \Log::info('Google login successful', [
                'customer_id' => $customer->id,
                'customer_email' => $customer->email,
                'session_id' => $session->getId(),
                'is_authenticated' => Auth::guard('customer')->check()
            ]);

            // Check if there's a checkout redirect
            if ($session->has('checkout_redirect')) {
                $redirectUrl = $session->get('checkout_redirect');
                $session->forget('checkout_redirect');
                $session->save();
                return redirect($redirectUrl)
                    ->with('success', __('messages.login_successful'));
            }

            // Always redirect to home page to show login status in navbar
            // The session will be automatically included in the response by Laravel's middleware
            return redirect()->route('home')
                ->with('success', __('messages.login_successful'));

        } catch (\Exception $e) {
            \Log::error('Google login error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => request()->all()
            ]);
            
            // Redirect to home instead of customer.login to avoid redirect loop
            return redirect()->route('home')
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

