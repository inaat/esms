<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Markdown;
use Illuminate\Mail\Mailables\Enveloper;

class SendMessageToEndUser extends Mailable
{
    use Queueable, SerializesModels;

    public $name = '';


    /**
     * Create a new message instance.
     */
    public function __construct($name)
    {
        $this->name = $name;
 
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->subject('Send Message To End User')
                    ->markdown('emails.reply_email');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
