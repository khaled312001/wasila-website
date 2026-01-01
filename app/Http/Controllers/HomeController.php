<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // Get services with fallback - get ALL active services
            try {
                $services = Service::where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                // Log for debugging
                Log::info('Services loaded: ' . $services->count());
            } catch (\Exception $serviceException) {
                Log::warning('Service query failed: ' . $serviceException->getMessage());
                $services = collect([]);
            }
            
            // Ensure services is always a collection
            if (!($services instanceof \Illuminate\Support\Collection)) {
                $services = collect([]);
            }
                
            return view('single-page', compact('services'));
        } catch (\Exception $e) {
            Log::error('HomeController error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Return view with empty services collection
            return view('single-page', ['services' => collect([])]);
        }
    }
}
