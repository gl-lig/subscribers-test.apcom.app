<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class AdminSubscriberController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscriber::query()->with(['orders.subscriptionType.translations']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('bat_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $subscribers = $query->latest()->paginate(20);

        return view('admin.subscribers.index', compact('subscribers'));
    }

    public function show(Subscriber $subscriber)
    {
        $subscriber->load([
            'orders' => fn ($q) => $q->with(['subscriptionType.translations', 'paymentLogs'])->latest(),
        ]);

        return view('admin.subscribers.show', compact('subscriber'));
    }
}
