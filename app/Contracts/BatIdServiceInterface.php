<?php

namespace App\Contracts;

interface BatIdServiceInterface
{
    public function verifyPhone(string $phone): ?array;

    public function notifySubscription(string $batId, string $event, array $data): bool;
}
