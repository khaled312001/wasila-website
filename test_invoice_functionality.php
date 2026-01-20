<?php
/**
 * اختبار وظيفة الفاتورة
 * 
 * هذا الملف يختبر:
 * 1. وجود حقل invoice_path في جدول orders
 * 2. دالة تحميل الفاتورة من MyFatoorah
 * 3. حفظ الفاتورة في التخزين
 * 4. عرض الفاتورة في صفحة الطلبات
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Http\Controllers\MyFatoorahController;

echo "========================================\n";
echo "اختبار وظيفة الفاتورة\n";
echo "========================================\n\n";

// 1. التحقق من وجود حقل invoice_path
echo "1. التحقق من وجود حقل invoice_path في جدول orders...\n";
try {
    $hasColumn = Schema::hasColumn('orders', 'invoice_path');
    if ($hasColumn) {
        echo "   ✓ حقل invoice_path موجود\n\n";
    } else {
        echo "   ✗ حقل invoice_path غير موجود - يجب تشغيل migration\n";
        echo "   قم بتشغيل: php artisan migrate\n\n";
    }
} catch (\Exception $e) {
    echo "   ✗ خطأ في التحقق من الحقل: " . $e->getMessage() . "\n\n";
}

// 2. التحقق من وجود مجلد invoices في التخزين
echo "2. التحقق من مجلد invoices في التخزين...\n";
try {
    $invoicesPath = 'invoices/' . date('Y/m');
    if (!Storage::disk('public')->exists($invoicesPath)) {
        Storage::disk('public')->makeDirectory($invoicesPath, 0755, true);
        echo "   ✓ تم إنشاء مجلد invoices\n\n";
    } else {
        echo "   ✓ مجلد invoices موجود\n\n";
    }
} catch (\Exception $e) {
    echo "   ✗ خطأ في إنشاء المجلد: " . $e->getMessage() . "\n\n";
}

// 3. التحقق من وجود طلب مدفوع للاختبار
echo "3. البحث عن طلب مدفوع للاختبار...\n";
try {
    $paidOrder = Order::where('payment_status', 'paid')
        ->whereNotNull('payment_reference')
        ->first();
    
    if ($paidOrder) {
        echo "   ✓ تم العثور على طلب مدفوع:\n";
        echo "      - رقم الطلب: {$paidOrder->order_number}\n";
        echo "      - رقم المرجع: {$paidOrder->payment_reference}\n";
        echo "      - مسار الفاتورة: " . ($paidOrder->invoice_path ?? 'غير موجود') . "\n\n";
        
        // 4. اختبار تحميل الفاتورة (إذا لم تكن موجودة)
        if (empty($paidOrder->invoice_path)) {
            echo "4. محاولة تحميل الفاتورة من MyFatoorah...\n";
            try {
                $invoiceId = $paidOrder->payment_reference;
                $invoicePath = MyFatoorahController::downloadInvoiceFromMyFatoorah(
                    $invoiceId, 
                    $paidOrder
                );
                
                if ($invoicePath) {
                    echo "   ✓ تم تحميل الفاتورة بنجاح\n";
                    echo "      - المسار: {$invoicePath}\n";
                    
                    // تحديث الطلب
                    $paidOrder->invoice_path = $invoicePath;
                    $paidOrder->save();
                    echo "      ✓ تم تحديث الطلب\n\n";
                } else {
                    echo "   ✗ فشل تحميل الفاتورة (قد يكون InvoiceId غير صحيح أو الفاتورة غير متاحة)\n\n";
                }
            } catch (\Exception $e) {
                echo "   ✗ خطأ في تحميل الفاتورة: " . $e->getMessage() . "\n\n";
            }
        } else {
            echo "4. الفاتورة موجودة بالفعل\n";
            if (Storage::disk('public')->exists($paidOrder->invoice_path)) {
                echo "   ✓ ملف الفاتورة موجود في التخزين\n";
                $fileSize = Storage::disk('public')->size($paidOrder->invoice_path);
                echo "      - حجم الملف: " . number_format($fileSize / 1024, 2) . " KB\n\n";
            } else {
                echo "   ✗ ملف الفاتورة غير موجود في التخزين\n\n";
            }
        }
    } else {
        echo "   ⚠ لا توجد طلبات مدفوعة للاختبار\n";
        echo "   يمكنك إنشاء طلب تجريبي ودفعه للاختبار\n\n";
    }
} catch (\Exception $e) {
    echo "   ✗ خطأ في البحث عن الطلبات: " . $e->getMessage() . "\n\n";
}

// 5. التحقق من إعدادات MyFatoorah
echo "5. التحقق من إعدادات MyFatoorah...\n";
try {
    $apiKey = env('MYFATOORAH_API_KEY') ?: config('myfatoorah.api_key');
    $testMode = env('MYFATOORAH_TEST_MODE') ?: config('myfatoorah.test_mode');
    $countryIso = config('myfatoorah.country_iso');
    
    if ($apiKey) {
        echo "   ✓ مفتاح API موجود: " . substr($apiKey, 0, 20) . "...\n";
    } else {
        echo "   ✗ مفتاح API غير موجود\n";
    }
    
    echo "   - وضع الاختبار: " . ($testMode ? 'نعم' : 'لا') . "\n";
    echo "   - رمز البلد: {$countryIso}\n\n";
} catch (\Exception $e) {
    echo "   ✗ خطأ في قراءة الإعدادات: " . $e->getMessage() . "\n\n";
}

// 6. التحقق من الرابط الرمزي للتخزين
echo "6. التحقق من الرابط الرمزي للتخزين...\n";
$storageLink = public_path('storage');
if (is_link($storageLink) || file_exists($storageLink)) {
    echo "   ✓ الرابط الرمزي موجود\n\n";
} else {
    echo "   ⚠ الرابط الرمزي غير موجود\n";
    echo "   قم بتشغيل: php artisan storage:link\n\n";
}

echo "========================================\n";
echo "انتهى الاختبار\n";
echo "========================================\n";
