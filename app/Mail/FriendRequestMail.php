<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FriendRequestMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $user;
    public $url;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $url)
    {
        $this->user = $user; // usuário que vai receber o e-mail
        $this->url = $url;   // link para as solicitações
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Você recebeu uma nova solicitação de amizade',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.friendrequest',
            with: [
                'user' => $this->user,
                'url' => $this->url,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
