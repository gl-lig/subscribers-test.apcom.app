<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionType;
use App\Services\DeeplinkService;
use Illuminate\Http\Request;

class DeeplinkController extends Controller
{
    public function handle(Request $request, DeeplinkService $deeplinkService)
    {
        $token = $request->query('token');

        if (! $token) {
            return redirect()->route('home');
        }

        // Validate and decode the token
        $data = $deeplinkService->validateToken($token);

        if (! $data) {
            return redirect()->route('home')
                ->with('deeplink_error', true);
        }

        // Verify the subscription type exists and is available
        $type = SubscriptionType::online()->find($data['type_id']);

        if (! $type) {
            return redirect()->route('home');
        }

        // Validate duration
        $duration = in_array($data['duration'], [12, 24, 36]) ? $data['duration'] : 12;

        // Set session values directly — no need to call bat-id API,
        // the bat_id and phone come from the signed token (trusted source)
        session()->put('bat_id', $data['bat_id']);
        session()->put('bat_phone', $data['phone']);
        session()->put('selected_type_id', $type->id);
        session()->put('selected_duration', $duration);

        // Redirect directly to cart — upgrade check happens in Cart::mount()
        return redirect()->route('cart');
    }
}
