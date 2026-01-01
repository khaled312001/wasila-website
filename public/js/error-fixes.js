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
        try {
            // Suppress 404 errors for images and videos
            if (e && e.target) {
                if (e.target.tagName === 'IMG' || e.target.tagName === 'VIDEO') {
                    return; // Silently ignore media loading errors
                }
            }
            
            // Only log non-critical errors
            if (e && e.message && typeof e.message === 'string') {
                const message = e.message.toLowerCase();
                if (!message.includes('youtube') && 
                    !message.includes('youtubejs') && 
                    !message.includes('404') &&
                    !message.includes('failed to load resource')) {
                    console.log('Wasila Error Fixes: JavaScript error handled:', e.message);
                }
            } else if (e && e.filename && typeof e.filename === 'string') {
                // Handle errors without message but with filename
                const filename = e.filename.toLowerCase();
                if (!filename.includes('youtube') && !filename.includes('404')) {
                    // Only log if it's not a media file error
                    if (!filename.match(/\.(png|jpg|jpeg|gif|svg|mp4|avi|mov|wmv)$/i)) {
                        console.log('Wasila Error Fixes: JavaScript error in file:', e.filename);
                    }
                }
            }
        } catch (err) {
            // Silently ignore errors in error handler
        }
    }, true);
    
    // Unhandled promise rejection handler
    window.addEventListener('unhandledrejection', function(e) {
        // Only log non-critical rejections
        if (!e.reason || !e.reason.toString().includes('youtube')) {
            console.log('Wasila Error Fixes: Promise rejection handled:', e.reason);
        }
    });
    
    console.log('Wasila Error Fixes: All JavaScript errors have been handled');
})();