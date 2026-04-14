<?php

namespace App\Services;

use App\Contracts\DatatransServiceInterface;
use App\Models\Order;
use App\Models\PaymentLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DatatransService implements DatatransServiceInterface
{
    private string $apiUrl;
    private string $merchantId;
    private string $signKey;

    public function __construct()
    {
        $this->apiUrl = config('datatrans.api_url');
        $this->merchantId = config('datatrans.merchant_id');
        $this->signKey = config('datatrans.sign_key');
    }

    public function initializeTransaction(Order $order, string $successUrl, string $cancelUrl, string $errorUrl): array
    {
        $amountInCents = (int) round((float) $order->price_paid * 100);

        $payload = [
            'currency' => 'CHF',
            'refno' => $order->order_number,
            'amount' => $amountInCents,
            'paymentMethods' => ['VIS', 'ECA', 'TWI'],
            'redirect' => [
                'successUrl' => $successUrl,
                'cancelUrl' => $cancelUrl,
                'errorUrl' => $errorUrl,
            ],
            'webhook' => [
                'url' => config('app.url') . '/webhook/datatrans',
            ],
            'option' => [
                'createAlias' => false,
            ],
        ];

        try {
            $response = Http::withBasicAuth($this->merchantId, $this->signKey)
                ->post("{$this->apiUrl}/v1/transactions", $payload);

            if ($response->successful()) {
                $data = $response->json();

                PaymentLog::create([
                    'order_id' => $order->id,
                    'datatrans_transaction_id' => $data['transactionId'] ?? null,
                    'event' => 'initialized',
                    'payload' => $data,
                    'received_at' => now(),
                ]);

                return [
                    'success' => true,
                    'transactionId' => $data['transactionId'] ?? null,
                    'redirectUrl' => "{$this->apiUrl}/v1/start/{$data['transactionId']}",
                ];
            }

            Log::error('Datatrans init failed', ['response' => $response->json()]);

            return ['success' => false, 'error' => $response->json('error', 'Unknown error')];
        } catch (\Exception $e) {
            Log::error('Datatrans init exception', ['error' => $e->getMessage()]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function handleWebhook(array $payload): array
    {
        $transactionId = $payload['transactionId'] ?? null;
        $status = $payload['status'] ?? 'unknown';

        $order = Order::where('datatrans_transaction_id', $transactionId)->first();

        if (! $order) {
            $order = Order::where('order_number', $payload['refno'] ?? '')->first();
        }

        if ($order) {
            PaymentLog::create([
                'order_id' => $order->id,
                'datatrans_transaction_id' => $transactionId,
                'event' => $status,
                'payload' => $payload,
                'received_at' => now(),
            ]);

            if (! $order->datatrans_transaction_id) {
                $order->update(['datatrans_transaction_id' => $transactionId]);
            }

            $paymentMethod = $this->resolvePaymentMethod($payload['paymentMethod'] ?? '');
            if ($paymentMethod) {
                $order->update(['payment_method' => $paymentMethod]);
            }
        }

        return [
            'transactionId' => $transactionId,
            'status' => $status,
            'order' => $order,
            'paymentMethod' => $paymentMethod ?? null,
        ];
    }

    public function getTransactionStatus(string $transactionId): array
    {
        try {
            $response = Http::withBasicAuth($this->merchantId, $this->signKey)
                ->get("{$this->apiUrl}/v1/transactions/{$transactionId}");

            return $response->json();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function resolvePaymentMethod(string $method): ?string
    {
        return match (strtoupper($method)) {
            'VIS' => 'visa',
            'ECA' => 'mastercard',
            'TWI' => 'twint',
            default => strtolower($method),
        };
    }
}
