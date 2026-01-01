<?php

namespace App\Helpers;

class PdfHelper
{
    /**
     * Fix Arabic text for PDF display
     * Wraps Arabic text with proper RTL marks to ensure correct rendering
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
        
        // Don't reverse - just return the text as is
        // The CSS direction: rtl and unicode-bidi: embed will handle it correctly
        // This preserves Arabic character connections
        return $text;
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

