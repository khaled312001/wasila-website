<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $dateRange;

    public function __construct($dateRange = 30)
    {
        $this->dateRange = $dateRange;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $startDate = now()->subDays($this->dateRange)->startOfDay();
        $endDate = now()->endOfDay();

        return Order::with(['orderItems.service'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'رقم الطلب',
            'تاريخ الطلب',
            'الخدمة',
            'الكمية',
            'سعر الوحدة',
            'المبلغ الإجمالي',
            'حالة الطلب',
            'حالة الدفع',
            'طريقة الدفع',
            'ملاحظات'
        ];
    }

    /**
     * @param Order $order
     * @return array
     */
    public function map($order): array
    {
        $rows = [];
        
        foreach ($order->orderItems as $item) {
            $rows[] = [
                $order->order_number,
                $order->created_at->format('Y-m-d H:i'),
                $item->service ? $item->service->name_ar : 'خدمة محذوفة',
                $item->quantity,
                number_format($item->unit_price, 2),
                number_format($item->total_price, 2),
                $this->getStatusText($order->status),
                $this->getPaymentStatusText($order->payment_status),
                $order->payment_method ?? 'غير محدد',
                $order->notes ?? ''
            ];
        }

        return $rows;
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15, // رقم الطلب
            'B' => 20, // تاريخ الطلب
            'C' => 30, // الخدمة
            'D' => 10, // الكمية
            'E' => 15, // سعر الوحدة
            'F' => 15, // المبلغ الإجمالي
            'G' => 15, // حالة الطلب
            'H' => 15, // حالة الدفع
            'I' => 20, // طريقة الدفع
            'J' => 30, // ملاحظات
        ];
    }

    /**
     * Get status text in Arabic
     */
    private function getStatusText($status)
    {
        $statuses = [
            'pending' => 'في الانتظار',
            'confirmed' => 'مؤكد',
            'processing' => 'قيد المعالجة',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي'
        ];

        return $statuses[$status] ?? $status;
    }

    /**
     * Get payment status text in Arabic
     */
    private function getPaymentStatusText($status)
    {
        $statuses = [
            'pending' => 'في الانتظار',
            'paid' => 'مدفوع',
            'failed' => 'فشل'
        ];

        return $statuses[$status] ?? $status;
    }
}
