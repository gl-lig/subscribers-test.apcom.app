<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show(string $token, InvoiceService $invoiceService)
    {
        $order = Order::where('invoice_token', $token)->firstOrFail();

        $path = $invoiceService->getPath($order);

        if (! $path) {
            $invoiceService->generate($order);
            $path = $invoiceService->getPath($order);
        }

        if (! $path) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $order->order_number . '.pdf"',
        ]);
    }
}
