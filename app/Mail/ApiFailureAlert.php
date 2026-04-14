<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApiFailureAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public string $event,
        public string $errorMessage
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'ALERTE: Échec notification API bat-id.ch — ' . $this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.api-failure',
            with: [
                'order' => $this->order,
                'event' => $this->event,
                'errorMessage' => $this->errorMessage,
            ],
        );
    }
}
