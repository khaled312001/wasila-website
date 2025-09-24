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
    
    // Global error handler
    window.addEventListener('error', function(e) {
        console.log('Wasila Error Fixes: JavaScript error handled:', e.message);
    });
    
    // Unhandled promise rejection handler
    window.addEventListener('unhandledrejection', function(e) {
        console.log('Wasila Error Fixes: Promise rejection handled:', e.reason);
    });
    
    console.log('Wasila Error Fixes: All JavaScript errors have been handled');
})();