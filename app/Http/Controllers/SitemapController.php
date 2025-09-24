<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->get();
        
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
        
        // Homepage
        $sitemap .= $this->addUrl(url('/'), now()->format('Y-m-d'), 'daily', '1.0');
        
        // Services page
        $sitemap .= $this->addUrl(url('/services'), now()->format('Y-m-d'), 'weekly', '0.8');
        
        // Individual services
        foreach ($services as $service) {
            $sitemap .= $this->addUrl(
                url('/services/' . $service->id), 
                $service->updated_at->format('Y-m-d'), 
                'weekly', 
                '0.6'
            );
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200, [
            'Content-Type' => 'application/xml; charset=utf-8'
        ]);
    }
    
    private function addUrl($loc, $lastmod, $changefreq, $priority)
    {
        return "    <url>\n" .
               "        <loc>{$loc}</loc>\n" .
               "        <lastmod>{$lastmod}</lastmod>\n" .
               "        <changefreq>{$changefreq}</changefreq>\n" .
               "        <priority>{$priority}</priority>\n" .
               "    </url>\n";
    }
}
