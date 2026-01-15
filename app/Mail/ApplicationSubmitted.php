<?php

namespace App\Mail;

use App\Models\Candidate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Candidate $candidate, public bool $showAdminActions = false)
    {
    }

    public function envelope(): Envelope
    {
        $subject = $this->showAdminActions
            ? "New Application Submitted - {$this->candidate->full_name}"
            : "Thanks for your application, {$this->candidate->full_name}";

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application-submitted',
        );
    }
}
