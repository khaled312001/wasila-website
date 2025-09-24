<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ContactMessageController;

// Arabic routes (default - no prefix)
Route::group(['prefix' => '', 'middleware' => ['web', 'setlocale:ar']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/services', [ServiceController::class, 'publicIndex'])->name('services');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::get('/orders/payment', [OrderController::class, 'payment'])->name('orders.payment');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/confirmation', [OrderController::class, 'confirmation'])->name('orders.confirmation');
    
    // Payment routes
    Route::get('/payment/callback', [OrderController::class, 'paymentCallback'])->name('payment.callback');
    Route::get('/payment/error', [OrderController::class, 'paymentError'])->name('payment.error');
    
    // Contact form
    Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store');
});

// English routes (with /en prefix)
Route::group(['prefix' => 'en', 'middleware' => ['web', 'setlocale:en']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home.en');
    Route::get('/services', [ServiceController::class, 'publicIndex'])->name('services.en');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create.en');
    Route::get('/orders/payment', [OrderController::class, 'payment'])->name('orders.payment.en');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store.en');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show.en');
    Route::get('/orders/confirmation', [OrderController::class, 'confirmation'])->name('orders.confirmation.en');
    
    // Payment routes
    Route::get('/payment/callback', [OrderController::class, 'paymentCallback'])->name('payment.callback.en');
    Route::get('/payment/error', [OrderController::class, 'paymentError'])->name('payment.error.en');
    
    // Contact form
    Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store.en');
});

// Language switching
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

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
        Route::resource('orders', OrderController::class)->only(['index', 'update']);
        Route::get('/orders/{order}', [OrderController::class, 'adminShow'])->name('orders.show');
        
        // MyFatoorah Management
        Route::prefix('myfatoorah')->name('myfatoorah.')->group(function () {
            Route::get('/', [App\Http\Controllers\MyFatoorahController::class, 'index'])->name('index');
            Route::get('/transactions', [App\Http\Controllers\MyFatoorahController::class, 'transactions'])->name('transactions');
            Route::get('/transactions/{order}', [App\Http\Controllers\MyFatoorahController::class, 'showTransaction'])->name('show');
            Route::post('/test-connection', [App\Http\Controllers\MyFatoorahController::class, 'testConnection'])->name('test-connection');
            Route::post('/refund/{order}', [App\Http\Controllers\MyFatoorahController::class, 'refund'])->name('refund');
            Route::get('/settings', [App\Http\Controllers\MyFatoorahController::class, 'settings'])->name('settings');
            Route::post('/settings', [App\Http\Controllers\MyFatoorahController::class, 'updateSettings'])->name('settings.update');
            Route::get('/export', [App\Http\Controllers\MyFatoorahController::class, 'exportTransactions'])->name('export');
            Route::post('/retry/{order}', [App\Http\Controllers\MyFatoorahController::class, 'retryPayment'])->name('retry');
        });
        
        
        // Analytics
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('index');
            Route::get('/export', [App\Http\Controllers\AnalyticsController::class, 'export'])->name('export');
        });
        
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
        });
    });
});
