<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ContactMessage;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contactMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(ContactMessage $contactMessage)
    {
        $this->contactMessage = $contactMessage;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('رسالة جديدة من نموذج التواصل - ' . $this->contactMessage->name)
                    ->view('emails.contact-message')
                    ->with([
                        'name' => $this->contactMessage->name,
                        'email' => $this->contactMessage->email,
                        'phone' => $this->contactMessage->phone,
                        'subject' => $this->contactMessage->subject,
                        'message' => $this->contactMessage->message,
                        'created_at' => $this->contactMessage->created_at,
                    ]);
    }
}

