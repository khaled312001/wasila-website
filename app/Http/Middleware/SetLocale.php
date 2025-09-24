<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from URL parameter first, then session, then default to Arabic
        $locale = $request->get('lang', $request->session()->get('locale', 'ar'));
        
        // Validate locale
        if (!in_array($locale, ['ar', 'en'])) {
            $locale = 'ar';
        }
        
        // Store the locale in the session
        $request->session()->put('locale', $locale);
        
        // Set application locale
        app()->setLocale($locale);
        
        // Set locale for Carbon dates
        if ($locale === 'ar') {
            \Carbon\Carbon::setLocale('ar');
        } else {
            \Carbon\Carbon::setLocale('en');
        }
        
        return $next($request);
    }
}
