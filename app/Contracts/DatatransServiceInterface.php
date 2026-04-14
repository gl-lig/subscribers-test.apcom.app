<?php

namespace App\Contracts;

use App\Models\Order;

interface DatatransServiceInterface
{
    public function initializeTransaction(Order $order, string $successUrl, string $cancelUrl, string $errorUrl): array;

    public function handleWebhook(array $payload): array;

    public function getTransactionStatus(string $transactionId): array;
}
