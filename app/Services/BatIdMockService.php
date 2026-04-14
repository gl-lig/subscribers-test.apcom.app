<?php

namespace App\Services;

use App\Contracts\BatIdServiceInterface;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Log;

class BatIdMockService implements BatIdServiceInterface
{
    public function verifyPhone(string $phone): ?array
    {
        $mockPhone = config('batid.mock_phone');
        $mockBatId = config('batid.mock_batid');

        Log::info('BatIdMockService::verifyPhone', ['phone' => $phone, 'mock_phone' => $mockPhone]);

        if ($phone === $mockPhone) {
            return [
                'bat_id' => $mockBatId,
                'phone' => $mockPhone,
            ];
        }

        return null;
    }

    public function notifySubscription(string $batId, string $event, array $data): bool
    {
        Log::info('BatIdMockService::notifySubscription', [
            'bat_id' => $batId,
            'event' => $event,
            'data' => $data,
        ]);

        ApiLog::create([
            'direction' => 'outgoing',
            'event' => $event,
            'bat_id' => $batId,
            'status' => 'success',
            'attempts' => 1,
            'payload' => $data,
            'response' => ['mock' => true, 'status' => 200],
        ]);

        return true;
    }
}
