<?php

namespace App\Services;

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Illuminate\Support\Facades\View;

class PdfService
{
    /**
     * Generate PDF from Blade view with proper Arabic support
     */
    public static function generate($view, $data = [], $options = [])
    {
        // Default options
        $defaultOptions = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P', // P = Portrait, L = Landscape
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 9,
            'tempDir' => storage_path('app/temp'),
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        // Get HTML content from Blade view
        $html = View::make($view, $data)->render();
        
        // Create mPDF instance
        $mpdf = new Mpdf($options);
        
        // Set RTL direction for Arabic
        $mpdf->SetDirectionality('rtl');
        
        // Set default font for Arabic (DejaVu Sans supports Arabic)
        $mpdf->SetDefaultFont('dejavusans');
        
        // Enable Arabic text shaping and bidirectional text
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        
        // Write HTML content
        $mpdf->WriteHTML($html);
        
        return $mpdf;
    }
    
    /**
     * Download PDF
     */
    public static function download($view, $data = [], $filename = 'document.pdf', $options = [])
    {
        $mpdf = self::generate($view, $data, $options);
        return $mpdf->Output($filename, 'D'); // D = Download
    }
    
    /**
     * Stream PDF
     */
    public static function stream($view, $data = [], $filename = 'document.pdf', $options = [])
    {
        $mpdf = self::generate($view, $data, $options);
        return $mpdf->Output($filename, 'I'); // I = Inline
    }
    
    /**
     * Save PDF to file
     */
    public static function save($view, $data = [], $filepath, $options = [])
    {
        $mpdf = self::generate($view, $data, $options);
        return $mpdf->Output($filepath, 'F'); // F = File
    }
}

