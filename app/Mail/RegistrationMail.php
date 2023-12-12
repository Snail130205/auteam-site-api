<?php

namespace App\Mail;

use App\Dto\RegistrationEmailDto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public RegistrationEmailDto $registrationEmailDto;

    /**
     * Create a new message instance.
     */
    public function __construct(RegistrationEmailDto $registrationEmailDto
    ) {
        $this->registrationEmailDto = $registrationEmailDto;
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.registrationTeamEmail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build(): RegistrationMail
    {
        return $this->view('emails.registrationTeamEmail', ['registrationEmailDto' => $this->registrationEmailDto])->subject('Регистрация - ' . $this->registrationEmailDto->olympiadName);
    }
}
