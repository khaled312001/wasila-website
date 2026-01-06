<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wasila - Deployment Tools</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f8f9fa;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .btn {
            background: #007bff;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 5px;
            display: inline-block;
            text-decoration: none;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #1e7e34; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-warning:hover { background: #e0a800; }
        .results {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            white-space: pre-line;
            font-family: monospace;
            max-height: 400px;
            overflow-y: auto;
        }
        .loading {
            display: none;
            color: #007bff;
            font-weight: bold;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 20px;
        }
        .alert {
            padding: 12px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .alert-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        .alert-warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
        .test-urls {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .test-urls a {
            display: block;
            color: #007bff;
            margin: 5px 0;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Wasila - Deployment Tools</h1>
            <p>Fix production image loading issues</p>
        </div>

        <div class="alert alert-info">
            <strong>Purpose:</strong> These tools fix the 403 Forbidden errors for images on the production website.
        </div>

        <div class="alert alert-warning">
            <strong>Security Note:</strong> Delete this page after fixing the issues by removing the deployment route from your routes file.
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <button class="btn btn-success" onclick="fixStorage()">
                🔗 Fix Storage & Permissions
            </button>
            
            <button class="btn btn-warning" onclick="fixImagePaths()">
                🗄️ Fix Database Image Paths
            </button>
            
            <button class="btn" onclick="testImages()">
                🧪 Test Image URLs
            </button>
        </div>

        <div class="loading" id="loading">
            ⏳ Processing... Please wait...
        </div>

        <div class="results" id="results" style="display: none;"></div>

        <div class="test-urls">
            <strong>Test these URLs after running fixes:</strong>
            <a href="{{ asset('storage/services/35LBAIj7gi8HLOWiIbGjiCs82UFloL9YDKhOePuS.png') }}" target="_blank">
                {{ asset('storage/services/35LBAIj7gi8HLOWiIbGjiCs82UFloL9YDKhOePuS.png') }}
            </a>
            <a href="{{ asset('storage/services/94ahRvARwxYgD3RzedyPEOIgk6BSESNYk98XQRgH.png') }}" target="_blank">
                {{ asset('storage/services/94ahRvARwxYgD3RzedyPEOIgk6BSESNYk98XQRgH.png') }}
            </a>
            <a href="{{ asset('storage/services/dFmRXwOqLlA8muu3Fp8iKyPld0PHe0b89AKGr2ty.png') }}" target="_blank">
                {{ asset('storage/services/dFmRXwOqLlA8muu3Fp8iKyPld0PHe0b89AKGr2ty.png') }}
            </a>
        </div>

        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #dee2e6; font-size: 14px; color: #6c757d;">
            <strong>Manual Steps if Tools Don't Work:</strong>
            <ol>
                <li>SSH into your server</li>
                <li>Run: <code>php artisan storage:link</code></li>
                <li>Run: <code>chmod -R 755 storage/</code></li>
                <li>Run: <code>chmod -R 644 storage/app/public/services/*</code></li>
                <li>Contact your hosting provider if issues persist</li>
            </ol>
        </div>
    </div>

    <script>
        // Set up CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        function showLoading() {
            document.getElementById('loading').style.display = 'block';
            document.getElementById('results').style.display = 'none';
        }
        
        function hideLoading() {
            document.getElementById('loading').style.display = 'none';
        }
        
        function showResults(results) {
            const resultsDiv = document.getElementById('results');
            resultsDiv.textContent = results.join('\n');
            resultsDiv.style.display = 'block';
        }
        
        function fixStorage() {
            showLoading();
            
            fetch('/deployment/fix-storage', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                showResults(data.results);
            })
            .catch(error => {
                hideLoading();
                showResults(['❌ Error: ' + error.message]);
            });
        }
        
        function fixImagePaths() {
            showLoading();
            
            fetch('/deployment/fix-paths', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                showResults(data.results);
            })
            .catch(error => {
                hideLoading();
                showResults(['❌ Error: ' + error.message]);
            });
        }
        
        function testImages() {
            const testUrls = [
                '{{ asset("storage/services/35LBAIj7gi8HLOWiIbGjiCs82UFloL9YDKhOePuS.png") }}',
                '{{ asset("storage/services/94ahRvARwxYgD3RzedyPEOIgk6BSESNYk98XQRgH.png") }}',
                '{{ asset("storage/services/dFmRXwOqLlA8muu3Fp8iKyPld0PHe0b89AKGr2ty.png") }}'
            ];
            
            showLoading();
            const results = ['=== Testing Image URLs ==='];
            
            Promise.all(testUrls.map(url => 
                fetch(url, { method: 'HEAD' })
                    .then(response => {
                        if (response.ok) {
                            results.push(`✅ ${url} - OK (${response.status})`);
                        } else {
                            results.push(`❌ ${url} - Error (${response.status})`);
                        }
                    })
                    .catch(error => {
                        results.push(`❌ ${url} - Network Error`);
                    })
            )).then(() => {
                hideLoading();
                showResults(results);
            });
        }
    </script>
</body>
</html>
