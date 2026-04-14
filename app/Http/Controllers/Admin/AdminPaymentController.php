<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentLog;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentLog::with(['order.subscriber']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('datatrans_transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('order', fn ($o) => $o->where('order_number', 'like', "%{$search}%"));
            });
        }

        if ($event = $request->get('event')) {
            $query->where('event', $event);
        }

        $payments = $query->latest('received_at')->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    public function show(PaymentLog $paymentLog)
    {
        $paymentLog->load(['order.subscriber', 'order.subscriptionType.translations']);
        return view('admin.payments.show', compact('paymentLog'));
    }
}
