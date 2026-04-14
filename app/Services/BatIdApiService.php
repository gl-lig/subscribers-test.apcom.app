<?php

namespace App\Services;

use App\Contracts\BatIdServiceInterface;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BatIdApiService implements BatIdServiceInterface
{
    public function verifyPhone(string $phone): ?array
    {
        $url = config('batid.outgoing_api_url') . '/verify-phone';
        $apiKey = config('batid.outgoing_api_key');
        $timeout = config('batid.outgoing_timeout', 10);

        try {
            $response = Http::timeout($timeout)
                ->withHeaders([
                    'X-Subscribers-API-Key' => $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($url, ['phone' => $phone]);

            if ($response->successful() && $response->json('bat_id')) {
                return [
                    'bat_id' => $response->json('bat_id'),
                    'phone' => $response->json('phone', $phone),
                ];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('BatIdApiService::verifyPhone failed', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function notifySubscription(string $batId, string $event, array $data): bool
    {
        $url = config('batid.outgoing_api_url') . '/subscription-notification';
        $apiKey = config('batid.outgoing_api_key');
        $timeout = config('batid.outgoing_timeout', 10);

        $payload = array_merge(['bat_id' => $batId, 'event' => $event], $data);
        $signature = hash_hmac('sha256', json_encode($payload), $apiKey);

        try {
            $response = Http::timeout($timeout)
                ->withHeaders([
                    'X-Subscribers-API-Key' => $apiKey,
                    'X-Signature' => $signature,
                    'Content-Type' => 'application/json',
                ])
                ->post($url, $payload);

            ApiLog::create([
                'direction' => 'outgoing',
                'event' => $event,
                'bat_id' => $batId,
                'status' => $response->successful() ? 'success' : 'failed',
                'attempts' => 1,
                'payload' => $payload,
                'response' => $response->json(),
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            ApiLog::create([
                'direction' => 'outgoing',
                'event' => $event,
                'bat_id' => $batId,
                'status' => 'error',
                'attempts' => 1,
                'payload' => $payload,
                'response' => ['error' => $e->getMessage()],
            ]);

            Log::error('BatIdApiService::notifySubscription failed', [
                'bat_id' => $batId,
                'event' => $event,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
