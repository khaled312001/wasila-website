<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wasila Charity - Manual Storage Fix</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 900px;
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
            background: #28a745;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            margin: 10px 5px;
            display: inline-block;
            text-decoration: none;
        }
        .btn:hover {
            background: #218838;
        }
        .btn-test {
            background: #007bff;
        }
        .btn-test:hover {
            background: #0056b3;
        }
        .results {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            white-space: pre-line;
            font-family: monospace;
            max-height: 500px;
            overflow-y: auto;
        }
        .loading {
            display: none;
            color: #28a745;
            font-weight: bold;
            font-size: 18px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
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
        .steps {
            background: #e9ecef;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .step {
            margin: 10px 0;
            padding: 10px;
            background: white;
            border-radius: 3px;
            border-left: 4px solid #28a745;
        }
        .test-urls {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .test-urls a {
            display: block;
            color: #007bff;
            margin: 5px 0;
            word-break: break-all;
            padding: 5px;
            background: white;
            border-radius: 3px;
        }
        .test-urls a:hover {
            background: #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Manual Storage Fix</h1>
            <p>Final solution for hosting providers with symlink restrictions</p>
        </div>

        <div class="alert alert-success">
            <strong>✅ Good News!</strong> You successfully set file permissions via SSH. This solution works around the symlink limitation by copying files directly.
        </div>

        <div class="alert alert-info">
            <strong>What this does:</strong> Copies all files from <code>storage/app/public</code> to <code>public/storage</code> without needing symlinks.
        </div>

        <div class="steps">
            <h3>What We Know:</h3>
            <div class="step">✅ All image files exist in storage/app/public</div>
            <div class="step">✅ Database paths are correct</div>
            <div class="step">✅ You set file permissions via SSH</div>
            <div class="step">❌ Symlinks don't work on your hosting provider</div>
            <div class="step">🔄 Need to copy files instead of symlinking</div>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <button class="btn" onclick="runManualFix()">
                🚀 Run Manual Storage Fix
            </button>
            
            <button class="btn btn-test" onclick="fixPortfolioPaths()">
                🔧 Fix Portfolio Database Paths
            </button>
            
            <button class="btn btn-test" onclick="testImages()">
                🧪 Test Image URLs
            </button>
        </div>

        <div class="loading" id="loading">
            ⏳ Processing... Please wait...
        </div>

        <div class="results" id="results" style="display: none;"></div>

        <div class="test-urls">
            <strong>🔗 Test these URLs after running the fix:</strong>
            
            <div style="margin: 10px 0; font-weight: bold; color: #007bff;">خدماتنا (Services):</div>
            <a href="{{ asset('storage/services/35LBAIj7gi8HLOWiIbGjiCs82UFloL9YDKhOePuS.png') }}" target="_blank">
                {{ asset('storage/services/35LBAIj7gi8HLOWiIbGjiCs82UFloL9YDKhOePuS.png') }}
            </a>
            <a href="{{ asset('storage/services/94ahRvARwxYgD3RzedyPEOIgk6BSESNYk98XQRgH.png') }}" target="_blank">
                {{ asset('storage/services/94ahRvARwxYgD3RzedyPEOIgk6BSESNYk98XQRgH.png') }}
            </a>
            <a href="{{ asset('storage/services/GdN8EyOD9hylXKEUnfZ7Dx0HC5DMhfHCLXhf3fpB.png') }}" target="_blank">
                {{ asset('storage/services/GdN8EyOD9hylXKEUnfZ7Dx0HC5DMhfHCLXhf3fpB.png') }}
            </a>
            
            <div style="margin: 10px 0; font-weight: bold; color: #28a745;">أعمالنا (Portfolio):</div>
            @php
                $portfolioItems = \App\Models\PortfolioItem::active()->take(3)->get();
            @endphp
            @if($portfolioItems->count() > 0)
                @foreach($portfolioItems as $item)
                <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank">
                    {{ asset('storage/' . $item->file_path) }}
                </a>
                @endforeach
            @else
                <div style="color: #856404; font-style: italic; margin-bottom: 10px;">No portfolio items found in database - using static images:</div>
                @for($i = 1; $i <= 5; $i++)
                <a href="{{ asset('storage/portfolio/' . $i . '.png') }}" target="_blank">
                    {{ asset('storage/portfolio/' . $i . '.png') }}
                </a>
                @endfor
            @endif
        </div>

        <div class="alert alert-warning" style="margin-top: 40px;">
            <strong>🔒 Security Note:</strong> Delete this page after fixing by removing the routes from your routes file.
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6; font-size: 14px; color: #6c757d;">
            <strong>If this doesn't work:</strong>
            <ul>
                <li>Contact your hosting provider (itegypt.org)</li>
                <li>Ask them to allow access to the <code>public/storage</code> directory</li>
                <li>Request they enable proper file permissions for uploaded files</li>
            </ul>
        </div>
    </div>

    <script>
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
        
        function runManualFix() {
            showLoading();
            
            fetch('/manual-storage-fix/run', {
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
        
        function fixPortfolioPaths() {
            showLoading();
            
            fetch('/manual-storage-fix/fix-portfolio-paths', {
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
                '{{ asset("storage/services/GdN8EyOD9hylXKEUnfZ7Dx0HC5DMhfHCLXhf3fpB.png") }}'
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
