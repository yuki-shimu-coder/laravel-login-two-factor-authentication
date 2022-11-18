<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class TwoFactorAuthPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $two_factor_token = '';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($two_factor_token)
    {
        $this->two_factor_token = $two_factor_token;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address('test@example.com', 'laravel-login-two-factor'),
            subject: '2段階認証のパスワードをご確認ください',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.two_factor_auth.password',
            with: ['two_factor_token' => $this->two_factor_token],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
