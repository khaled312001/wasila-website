<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CustomerAuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        // If already logged in, redirect to home (not dashboard) to show login status
        if (Auth::guard('customer')->check()) {
            return redirect()->route('home')
                ->with('info', __('messages.already_logged_in'));
        }
        
        return view('customer.auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => __('messages.email_required'),
            'email.email' => __('messages.email_invalid'),
            'password.required' => __('messages.password_required'),
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::guard('customer')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Check if there's a checkout redirect
            if (session()->has('checkout_redirect')) {
                $redirectUrl = session('checkout_redirect');
                session()->forget('checkout_redirect');
                return redirect($redirectUrl)
                    ->with('success', __('messages.login_successful'));
            }

            return redirect()->intended(route('customer.dashboard'))
                ->with('success', __('messages.login_successful'));
        }

        return back()->withErrors([
            'email' => __('messages.login_failed'),
        ])->onlyInput('email');
    }

    /**
     * Show the registration form
     */
    public function showRegisterForm()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.dashboard');
        }
        
        return view('customer.auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required' => __('messages.name_required'),
            'email.required' => __('messages.email_required'),
            'email.email' => __('messages.email_invalid'),
            'email.unique' => __('messages.email_already_exists'),
            'password.required' => __('messages.password_required'),
            'password.confirmed' => __('messages.password_confirmation_mismatch'),
            'password.min' => __('messages.password_min_length'),
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'locale' => app()->getLocale(),
            'email_verified_at' => now(),
        ]);

        Auth::guard('customer')->login($customer);

        // Check if there's a checkout redirect
        if (session()->has('checkout_redirect')) {
            $redirectUrl = session('checkout_redirect');
            session()->forget('checkout_redirect');
            return redirect($redirectUrl)
                ->with('success', __('messages.registration_successful'));
        }

        return redirect()->route('customer.dashboard')
            ->with('success', __('messages.registration_successful'));
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', __('messages.logout_successful'));
    }
}
