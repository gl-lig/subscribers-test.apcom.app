<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentLog;
use App\Models\Subscriber;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminExportController extends Controller
{
    public function export(string $type): StreamedResponse
    {
        return match ($type) {
            'subscribers' => $this->exportSubscribers(),
            'orders' => $this->exportOrders(),
            'payments' => $this->exportPayments(),
            default => abort(404),
        };
    }

    private function exportSubscribers(): StreamedResponse
    {
        return $this->streamCsv('abonnes.csv', ['bat-ID', 'Téléphone', 'Créé le'], function () {
            Subscriber::chunk(500, function ($subscribers) {
                foreach ($subscribers as $s) {
                    echo $this->csvLine([$s->bat_id, $s->phone, $s->created_at->format('d.m.Y H:i')]);
                }
            });
        });
    }

    private function exportOrders(): StreamedResponse
    {
        return $this->streamCsv('commandes.csv', [
            'N° Commande', 'bat-ID', 'Type', 'Durée', 'Prix payé', 'Statut', 'Début', 'Fin', 'Paiement',
        ], function () {
            Order::with(['subscriber', 'subscriptionType.translations'])->chunk(500, function ($orders) {
                foreach ($orders as $o) {
                    echo $this->csvLine([
                        $o->order_number,
                        $o->subscriber->bat_id ?? '',
                        $o->subscriptionType->translation('fr')?->name ?? '',
                        $o->duration_months . ' mois',
                        'CHF ' . $o->price_paid,
                        $o->status,
                        $o->starts_at->format('d.m.Y'),
                        $o->expires_at->format('d.m.Y'),
                        $o->payment_method ?? '',
                    ]);
                }
            });
        });
    }

    private function exportPayments(): StreamedResponse
    {
        return $this->streamCsv('paiements.csv', [
            'ID Transaction', 'N° Commande', 'Événement', 'Date',
        ], function () {
            PaymentLog::with('order')->chunk(500, function ($logs) {
                foreach ($logs as $log) {
                    echo $this->csvLine([
                        $log->datatrans_transaction_id ?? '',
                        $log->order->order_number ?? '',
                        $log->event,
                        $log->received_at?->format('d.m.Y H:i') ?? '',
                    ]);
                }
            });
        });
    }

    private function streamCsv(string $filename, array $headers, callable $body): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $body) {
            echo "\xEF\xBB\xBF"; // UTF-8 BOM for Excel
            echo $this->csvLine($headers);
            $body();
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function csvLine(array $fields): string
    {
        $escaped = array_map(fn ($f) => '"' . str_replace('"', '""', (string) $f) . '"', $fields);
        return implode(';', $escaped) . "\n";
    }
}
