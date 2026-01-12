<?php

namespace App\Services;

use Illuminate\Support\Facades\View;

class PdfService
{
    /**
     * Generate PDF from Blade view with proper Arabic support
     */
    public static function generate($view, $data = [], $options = [])
    {
        // Check if mPDF is available
        if (!class_exists('\Mpdf\Mpdf')) {
            throw new \Exception('mPDF library is not installed. Please run: composer require mpdf/mpdf');
        }
        
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
        
        // Ensure temp directory exists
        if (!is_dir($options['tempDir'])) {
            mkdir($options['tempDir'], 0755, true);
        }
        
        // Get HTML content from Blade view
        $html = View::make($view, $data)->render();
        
        // Create mPDF instance
        $mpdf = new \Mpdf\Mpdf($options);
        
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
