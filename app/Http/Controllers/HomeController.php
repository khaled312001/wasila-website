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
            $services = Service::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
                
            return view('single-page', compact('services'));
        } catch (\Exception $e) {
            Log::error('HomeController error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return a simple error view or fallback
            return view('single-page', ['services' => collect([])]);
        }
    }
}
