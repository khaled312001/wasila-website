<?php

namespace App\Helpers;

class PdfHelper
{
    /**
     * Fix Arabic text for PDF display
     * DomPDF reverses Arabic text, so we reverse it first to compensate
     */
    public static function fixArabic($text)
    {
        if (empty($text) || !is_string($text)) {
            return $text;
        }
        
        // Check if text contains Arabic characters
        if (!preg_match('/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u', $text)) {
            return $text;
        }
        
        // Simple reversal: reverse the entire string
        // This works because DomPDF will reverse it again, making it correct
        return mb_strrev($text, 'UTF-8');
    }
}

if (!function_exists('mb_strrev')) {
    /**
     * Reverse a multibyte string
     */
    function mb_strrev($string, $encoding = null)
    {
        if ($encoding === null) {
            $encoding = mb_internal_encoding();
        }
        
        $length = mb_strlen($string, $encoding);
        $reversed = '';
        
        while ($length-- > 0) {
            $reversed .= mb_substr($string, $length, 1, $encoding);
        }
        
        return $reversed;
    }
}

