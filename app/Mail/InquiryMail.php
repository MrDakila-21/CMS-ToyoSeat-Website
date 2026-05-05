<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $mail = $this->from(config('mail.from.address'), config('mail.from.name'))
            ->replyTo($this->data['email'] ?? config('mail.from.address'), $this->data['name'] ?? null)
            ->subject('New Inquiry Message')
            ->view('emails.inquiry')
            ->with(['data' => $this->data]);

        if (! empty($this->data['attachment_path'])) {
            $mail->attachFromStorageDisk(
                'public',
                $this->data['attachment_path'],
                $this->data['attachment_original_name'] ?? null,
                ['mime' => $this->data['attachment_mime'] ?? null]
            );
        }

        return $mail;
    }
}