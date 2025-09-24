<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function index()
    {
        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n\n";
        $robots .= "# Sitemap\n";
        $robots .= "Sitemap: " . url('/sitemap.xml') . "\n\n";
        $robots .= "# Crawl-delay\n";
        $robots .= "Crawl-delay: 1\n\n";
        $robots .= "# Disallow admin areas\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /storage/\n";
        $robots .= "Disallow: /vendor/\n";
        $robots .= "Disallow: /node_modules/\n\n";
        $robots .= "# Allow important pages\n";
        $robots .= "Allow: /services\n";
        $robots .= "Allow: /orders\n";
        $robots .= "Allow: /about\n";
        $robots .= "Allow: /contact\n";
        
        return response($robots, 200, [
            'Content-Type' => 'text/plain'
        ]);
    }
}
