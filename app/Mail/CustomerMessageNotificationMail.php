<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\CustomerMessage;

class CustomerMessageNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(CustomerMessage $customerMessage)
    {
        $this->customerMessage = $customerMessage;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $message = $this->customerMessage->load('customer', 'order');
        
        $subject = 'رسالة جديدة من العميل';
        if ($message->order) {
            $subject .= ' - طلب #' . $message->order->order_number;
        }
        
        return $this->subject($subject)
                    ->view('emails.customer-message-notification')
                    ->with([
                        'message' => $message,
                        'customer' => $message->customer,
                        'order' => $message->order,
                        'messageText' => $message->message,
                        'hasFile' => !empty($message->file_path),
                        'fileName' => $message->file_name,
                        'fileType' => $message->file_type,
                        'createdAt' => $message->created_at,
                    ]);
    }
}
