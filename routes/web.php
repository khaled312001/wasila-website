<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\MessageController as CustomerMessageController;

// Arabic routes (default - no prefix)
Route::group(['prefix' => '', 'middleware' => ['web', 'setlocale:ar']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/services', [ServiceController::class, 'publicIndex'])->name('services');
    Route::get('/portfolio', [App\Http\Controllers\PortfolioController::class, 'index'])->name('portfolio');
    Route::get('/orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders', [OrderController::class, 'store'])->middleware('auth:customer')->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/confirmation', [OrderController::class, 'confirmation'])->name('orders.confirmation');
    
    // Payment routes
    Route::get('/payment/callback', [OrderController::class, 'paymentCallback'])->name('payment.callback');
    Route::get('/payment/error', [OrderController::class, 'paymentError'])->name('payment.error');
    
    // MyFatoorah routes
    Route::get('/myfatoorah', [App\Http\Controllers\MyFatoorahController::class, 'index'])->name('myfatoorah.index');
    Route::get('/myfatoorah/checkout', [App\Http\Controllers\MyFatoorahController::class, 'checkout'])->name('myfatoorah.checkout');
    Route::get('/myfatoorah/callback', [App\Http\Controllers\MyFatoorahController::class, 'callback'])->name('myfatoorah.callback');
    Route::post('/myfatoorah/webhook', [App\Http\Controllers\MyFatoorahController::class, 'webhook'])->name('myfatoorah.webhook');
    
    // Contact form
    Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store');
});

// English routes (with /en prefix)
Route::group(['prefix' => 'en', 'middleware' => ['web', 'setlocale:en']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home.en');
    Route::get('/services', [ServiceController::class, 'publicIndex'])->name('services.en');
    Route::get('/portfolio', [App\Http\Controllers\PortfolioController::class, 'index'])->name('portfolio.en');
    Route::get('/orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout.en');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store.en');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show.en');
    Route::get('/orders/confirmation', [OrderController::class, 'confirmation'])->name('orders.confirmation.en');
    
    // Payment routes
    Route::get('/payment/callback', [OrderController::class, 'paymentCallback'])->name('payment.callback.en');
    Route::get('/payment/error', [OrderController::class, 'paymentError'])->name('payment.error.en');
    
    // MyFatoorah routes
    Route::get('/myfatoorah', [App\Http\Controllers\MyFatoorahController::class, 'index'])->name('myfatoorah.index.en');
    Route::get('/myfatoorah/checkout', [App\Http\Controllers\MyFatoorahController::class, 'checkout'])->name('myfatoorah.checkout.en');
    Route::get('/myfatoorah/callback', [App\Http\Controllers\MyFatoorahController::class, 'callback'])->name('myfatoorah.callback.en');
    Route::post('/myfatoorah/webhook', [App\Http\Controllers\MyFatoorahController::class, 'webhook'])->name('myfatoorah.webhook.en');
    
    // Contact form
    Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store.en');
});

// Language switching
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Google OAuth routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');
// Alternative route for compatibility
Route::get('/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Customer Auth routes
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\CustomerAuthController::class, 'login'])->name('login.post');
    Route::get('/register', [App\Http\Controllers\Auth\CustomerAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\CustomerAuthController::class, 'register'])->name('register.post');
    Route::post('/logout', [App\Http\Controllers\Auth\CustomerAuthController::class, 'logout'])->name('logout');
    
    // Customer login route (with email parameter) - must have a valid email format
    Route::get('/login/{email}', function($email) {
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('admin.login')
                ->with('error', 'البريد الإلكتروني غير صحيح');
        }
        
        $customer = \App\Models\Customer::where('email', $email)->first();
        if ($customer) {
            auth('customer')->login($customer);
            return redirect()->route('customer.dashboard')
                ->with('success', 'تم تسجيل الدخول بنجاح كـ ' . $customer->name);
        }
        return redirect()->route('home')
            ->with('error', 'العميل غير موجود');
    })->where('email', '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}')->name('login.email');
});

// Admin login route
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');

// Customer Dashboard routes
Route::prefix('customer')->name('customer.')->middleware('auth:customer')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    
    // Orders
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [CustomerOrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('/orders/{order}/invoice/download', [CustomerOrderController::class, 'downloadInvoice'])->name('orders.invoice.download');
    
    // Messages
    Route::get('/messages', [CustomerMessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [CustomerMessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/get', [CustomerMessageController::class, 'getMessages'])->name('messages.get');
});

// Deployment tools (remove these routes after fixing production issues)
Route::get('/deployment-tools', [App\Http\Controllers\DeploymentController::class, 'index'])->name('deployment.tools');
Route::post('/deployment/fix-storage', [App\Http\Controllers\DeploymentController::class, 'fixStorage'])->name('deployment.fix-storage');
Route::post('/deployment/fix-paths', [App\Http\Controllers\DeploymentController::class, 'fixImagePaths'])->name('deployment.fix-paths');

// Manual storage fix (for hosting providers that don't support symlinks)
Route::get('/manual-storage-fix', [App\Http\Controllers\ManualStorageFixController::class, 'index'])->name('manual.storage.fix');
Route::post('/manual-storage-fix/run', [App\Http\Controllers\ManualStorageFixController::class, 'fixStorageManually'])->name('manual.storage.fix.run');
Route::post('/manual-storage-fix/create-sample-portfolio', [App\Http\Controllers\ManualStorageFixController::class, 'createSamplePortfolio'])->name('manual.storage.fix.create.sample.portfolio');
Route::post('/manual-storage-fix/activate-portfolio', [App\Http\Controllers\ManualStorageFixController::class, 'activatePortfolioItems'])->name('manual.storage.fix.activate.portfolio');
Route::post('/manual-storage-fix/fix-portfolio-paths', [App\Http\Controllers\ManualStorageFixController::class, 'fixPortfolioPaths'])->name('manual.storage.fix.portfolio.paths');

// Sitemap and Robots
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [App\Http\Controllers\RobotsController::class, 'index'])->name('robots');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    
    Route::middleware('auth.admin')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('services', ServiceController::class);
        Route::post('/services/sync-images', [ServiceController::class, 'syncAllImages'])->name('services.sync-images');
        Route::resource('orders', OrderController::class)->only(['index', 'update', 'destroy']);
        Route::get('/orders/{order}', [OrderController::class, 'adminShow'])->name('orders.show');
        Route::post('/orders/delete-multiple', [OrderController::class, 'destroyMultiple'])->name('orders.delete-multiple');
        Route::get('/orders/export/excel', [OrderController::class, 'exportExcel'])->name('orders.export.excel');
        Route::get('/orders/export/pdf', [AdminController::class, 'exportOrdersPDF'])->name('orders.export.pdf');
        
        // Order Documentation
        Route::post('/orders/{order}/documentation', [AdminController::class, 'uploadDocumentation'])->name('orders.documentation.upload');
        Route::delete('/documentation/{documentation}', [AdminController::class, 'deleteDocumentation'])->name('documentation.delete');
        
        // Customers Management
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/', [AdminController::class, 'customersIndex'])->name('index');
            Route::get('/{customer}', [AdminController::class, 'customersShow'])->name('show');
            Route::get('/{customer}/orders', [AdminController::class, 'customersOrders'])->name('orders');
            Route::get('/{customer}/messages', [AdminController::class, 'customersMessages'])->name('messages');
            Route::get('/{customer}/messages/get', [AdminController::class, 'getCustomerMessages'])->name('messages.get');
            Route::get('/export/excel', [AdminController::class, 'exportCustomersExcel'])->name('export.excel');
            Route::get('/export/pdf', [AdminController::class, 'exportCustomersPDF'])->name('export.pdf');
        });
        
        // Customer Messages
        Route::get('/customer-messages', [AdminController::class, 'customerMessages'])->name('customer.messages');
        Route::post('/customer-messages/{message}/reply', [AdminController::class, 'replyToCustomer'])->name('customer.messages.reply');
        Route::post('/customers/{customer}/messages/send', [AdminController::class, 'sendMessageToCustomer'])->name('customers.messages.send');
        Route::get('/customer-messages/{message}/edit', [AdminController::class, 'editCustomerMessage'])->name('customer.messages.edit');
        Route::put('/customer-messages/{message}', [AdminController::class, 'updateCustomerMessage'])->name('customer.messages.update');
        Route::delete('/customer-messages/{message}', [AdminController::class, 'destroyCustomerMessage'])->name('customer.messages.destroy');
        
        // Statistics PDF Export
        Route::get('/statistics/export/pdf', [AdminController::class, 'exportStatisticsPDF'])->name('statistics.export.pdf');
        
        // MyFatoorah Management
        Route::prefix('myfatoorah')->name('myfatoorah.')->group(function () {
            Route::get('/', [App\Http\Controllers\MyFatoorahController::class, 'adminIndex'])->name('index');
            Route::get('/transactions', [App\Http\Controllers\MyFatoorahController::class, 'transactions'])->name('transactions');
            Route::get('/transactions/{order}', [App\Http\Controllers\MyFatoorahController::class, 'show'])->name('show');
            Route::post('/test-connection', [App\Http\Controllers\MyFatoorahController::class, 'testConnection'])->name('test-connection');
            Route::post('/refund/{order}', [App\Http\Controllers\MyFatoorahController::class, 'refund'])->name('refund');
            Route::get('/settings', [App\Http\Controllers\MyFatoorahController::class, 'settings'])->name('settings');
            Route::post('/settings', [App\Http\Controllers\MyFatoorahController::class, 'updateSettings'])->name('settings.update');
            Route::get('/export', [App\Http\Controllers\MyFatoorahController::class, 'export'])->name('export');
            Route::post('/retry/{order}', [App\Http\Controllers\MyFatoorahController::class, 'retry'])->name('retry');
        });
        
        
        // Analytics
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('index');
            Route::get('/export', [App\Http\Controllers\AnalyticsController::class, 'export'])->name('export');
        });
        
        // MyFatoorah Export
        Route::get('/myfatoorah/export', [App\Http\Controllers\MyFatoorahController::class, 'export'])->name('myfatoorah.export');
        
        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [App\Http\Controllers\SettingsController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\SettingsController::class, 'update'])->name('update');
            Route::post('/myfatoorah', [App\Http\Controllers\SettingsController::class, 'updateMyFatoorah'])->name('update-myfatoorah');
            Route::post('/backup', [App\Http\Controllers\SettingsController::class, 'backup'])->name('backup');
            Route::post('/clear-cache', [App\Http\Controllers\SettingsController::class, 'clearCache'])->name('clear-cache');
            Route::get('/system-info', [App\Http\Controllers\SettingsController::class, 'systemInfo'])->name('system-info');
        });
        
        // Contact Messages
        Route::prefix('contact-messages')->name('contact-messages.')->group(function () {
            Route::get('/', [ContactMessageController::class, 'index'])->name('index');
            Route::get('/{contactMessage}', [ContactMessageController::class, 'show'])->name('show');
            Route::delete('/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('destroy');
            Route::post('/{contactMessage}/mark-read', [ContactMessageController::class, 'markAsRead'])->name('mark-read');
            Route::post('/{contactMessage}/mark-unread', [ContactMessageController::class, 'markAsUnread'])->name('mark-unread');
            Route::get('/api/unread-count', [ContactMessageController::class, 'getUnreadCount'])->name('unread-count');
            Route::post('/{contactMessage}/reply', [ContactMessageController::class, 'sendReply'])->name('reply');
            Route::get('/{contactMessage}/replies', [ContactMessageController::class, 'getReplies'])->name('replies');
        });
        
        // Content Management
        Route::prefix('content-management')->name('content-management.')->group(function () {
            Route::get('/', [App\Http\Controllers\ContentManagementController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\ContentManagementController::class, 'update'])->name('update');
            Route::post('/delete-file', [App\Http\Controllers\ContentManagementController::class, 'deleteFile'])->name('delete-file');
        });
        
        // Portfolio Management
        Route::prefix('portfolio')->name('portfolio.')->group(function () {
            Route::get('/', [App\Http\Controllers\PortfolioController::class, 'adminIndex'])->name('index');
            Route::get('/create', [App\Http\Controllers\PortfolioController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\PortfolioController::class, 'store'])->name('store');
            Route::post('/add-all-images', [App\Http\Controllers\PortfolioController::class, 'addAllImagesFromFolder'])->name('add-all-images');
            Route::post('/sync-images', [App\Http\Controllers\PortfolioController::class, 'syncAllImages'])->name('sync-images');
            Route::get('/{portfolioItem}/edit', [App\Http\Controllers\PortfolioController::class, 'edit'])->name('edit');
            Route::put('/{portfolioItem}', [App\Http\Controllers\PortfolioController::class, 'update'])->name('update');
            Route::delete('/{portfolioItem}', [App\Http\Controllers\PortfolioController::class, 'destroy'])->name('destroy');
        });
    });
});
