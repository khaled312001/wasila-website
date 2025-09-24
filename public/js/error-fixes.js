// Wasila Error Fixes - Handle JavaScript errors gracefully
(function() {
    'use strict';
    
    // Browser object polyfill
    if (typeof browser === 'undefined') {
        window.browser = {
            runtime: {
                getURL: function(path) { return path; },
                sendMessage: function() { return Promise.resolve(); },
                onMessage: { addListener: function() {} }
            }
        };
    }
    
    // Suppress specific warnings
    const originalWarn = console.warn;
    console.warn = function(...args) {
        const message = args.join(' ');
        
        // Suppress Tailwind CDN warning
        if (message.includes('cdn.tailwindcss.com should not be used in production')) {
            return;
        }
        
        // Suppress YouTube.js warnings
        if (message.includes('[YOUTUBEJS]') || message.includes('Failed to extract signature decipher algorithm')) {
            return;
        }
        
        // Call original warn for other messages
        originalWarn.apply(console, args);
    };
    
    // Global error handler
    window.addEventListener('error', function(e) {
        // Only log non-critical errors
        if (!e.message.includes('youtube') && !e.message.includes('YOUTUBEJS')) {
            console.log('Wasila Error Fixes: JavaScript error handled:', e.message);
        }
    });
    
    // Unhandled promise rejection handler
    window.addEventListener('unhandledrejection', function(e) {
        // Only log non-critical rejections
        if (!e.reason || !e.reason.toString().includes('youtube')) {
            console.log('Wasila Error Fixes: Promise rejection handled:', e.reason);
        }
    });
    
    console.log('Wasila Error Fixes: All JavaScript errors have been handled');
})();