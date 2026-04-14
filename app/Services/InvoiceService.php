<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generate(Order $order, string $locale = 'fr'): string
    {
        $order->load(['subscriber', 'subscriptionType.translations']);

        $data = [
            'order' => $order,
            'subscriber' => $order->subscriber,
            'type' => $order->subscriptionType,
            'typeName' => $order->subscriptionType->translation($locale)?->name ?? 'N/A',
            'locale' => $locale,
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data);
        $pdf->setPaper('a4');

        $filename = "{$order->order_number}.pdf";
        $path = "invoices/{$filename}";

        Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }

    public function getPath(Order $order): ?string
    {
        $path = "invoices/{$order->order_number}.pdf";

        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->path($path);
        }

        return null;
    }
}
