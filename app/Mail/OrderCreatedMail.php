<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $order = $this->order->load('orderItems.service');
        
        return $this->subject('طلب جديد - ' . $order->order_number)
                    ->view('emails.order-created')
                    ->with([
                        'order' => $order,
                        'orderNumber' => $order->order_number,
                        'customerName' => $order->customer_name,
                        'customerEmail' => $order->customer_email,
                        'customerPhone' => $order->customer_phone,
                        'customerAddress' => $order->customer_address,
                        'totalAmount' => $order->total_amount,
                        'paymentMethod' => $order->payment_method,
                        'paymentStatus' => $order->payment_status,
                        'status' => $order->status,
                        'orderItems' => $order->orderItems,
                        'createdAt' => $order->created_at,
                    ]);
    }
}
