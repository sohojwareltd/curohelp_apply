<?php

namespace App\Mail;

use App\Models\WorkerInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class WorkerInvoiceUploaded extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public WorkerInvoice $invoice
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Worker Invoice Uploaded - ' . ($this->invoice->worker->name ?? 'Worker'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.worker-invoice-uploaded',
            with: [
                'invoice' => $this->invoice,
                'worker' => $this->invoice->worker,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        // Attach the invoice PDF file
        if ($this->invoice->file_path && Storage::disk('public')->exists($this->invoice->file_path)) {
            $attachments[] = Attachment::fromPath(
                Storage::disk('public')->path($this->invoice->file_path)
            )->as($this->invoice->original_name ?: basename($this->invoice->file_path));
        }

        return $attachments;
    }
}
