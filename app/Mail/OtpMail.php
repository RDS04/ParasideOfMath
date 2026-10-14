<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $name;
    public string $role;

    /**
     * Create a new message instance.
     */
    public function __construct(string $otp, string $name, string $role)
    {
        $this->otp = $otp;
        $this->name = $name;
        $this->role = $role;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = ($this->role === 'reset_password' || str_starts_with($this->role, 'reset_'))
            ? 'Kode OTP Reset Kata Sandi - Paradise of Math'
            : 'Kode OTP Verifikasi Registrasi - Paradise of Math';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
        );
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
