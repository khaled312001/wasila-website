<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Switch language
     */
    public function switch(Request $request, $locale)
    {
        // Validate the locale
        if (!in_array($locale, ['ar', 'en'])) {
            return redirect()->back()->with('error', 'Invalid language');
        }

        // Store the locale in the session
        $request->session()->put('locale', $locale);
        
        // Set the application locale immediately
        app()->setLocale($locale);
        
        // Set locale for Carbon dates
        if ($locale === 'ar') {
            \Carbon\Carbon::setLocale('ar');
        } else {
            \Carbon\Carbon::setLocale('en');
        }
        
        // Clear any cached views to ensure the new language is applied
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
        
        // Force session save
        $request->session()->save();
        
        // Get the previous URL or default to home
        $previousUrl = $request->header('referer', route('home'));
        
        // Parse the URL to determine the current route
        $parsedUrl = parse_url($previousUrl);
        $path = $parsedUrl['path'] ?? '/';
        
        // Determine the target route based on the new locale
        if ($locale === 'en') {
            // Switch to English routes
            if ($path === '/' || $path === '') {
                $targetUrl = route('home.en');
            } elseif (str_starts_with($path, '/services')) {
                $targetUrl = route('services.en');
            } elseif (str_starts_with($path, '/orders/create')) {
                $targetUrl = route('orders.create.en');
            } elseif (str_starts_with($path, '/orders/') && !str_contains($path, '/create')) {
                // Extract order ID from path
                $orderId = basename($path);
                $targetUrl = route('orders.show.en', $orderId);
            } elseif (str_starts_with($path, '/orders/confirmation')) {
                $targetUrl = route('orders.confirmation.en');
            } else {
                $targetUrl = route('home.en');
            }
        } else {
            // Switch to Arabic routes (default)
            if ($path === '/en' || $path === '/en/') {
                $targetUrl = route('home');
            } elseif (str_starts_with($path, '/en/services')) {
                $targetUrl = route('services');
            } elseif (str_starts_with($path, '/en/orders/create')) {
                $targetUrl = route('orders.create');
            } elseif (str_starts_with($path, '/en/orders/') && !str_contains($path, '/create')) {
                // Extract order ID from path
                $orderId = basename($path);
                $targetUrl = route('orders.show', $orderId);
            } elseif (str_starts_with($path, '/en/orders/confirmation')) {
                $targetUrl = route('orders.confirmation');
            } else {
                $targetUrl = route('home');
            }
        }
        
        // Add query parameters if they exist
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
            unset($queryParams['lang']);
            unset($queryParams['t']);
            $queryString = http_build_query($queryParams);
            if ($queryString) {
                $targetUrl .= '?' . $queryString;
            }
        }
        
        // Redirect to the appropriate route
        return redirect($targetUrl)
            ->with('language_switched', $locale)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
