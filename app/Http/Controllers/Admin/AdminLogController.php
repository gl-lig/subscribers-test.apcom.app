<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\NotifyBatIdJob;
use App\Mail\ApiDocumentationMail;
use App\Models\AdminActivityLog;
use App\Models\ApiLog;
use App\Models\Order;
use App\Models\SubscriptionType;
use App\Services\AdminLogService;
use App\Services\DeeplinkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminLogController extends Controller
{
    public function activity(Request $request)
    {
        $query = AdminActivityLog::with('admin');

        if ($module = $request->get('module')) {
            $query->where('module', $module);
        }

        $logs = $query->latest()->paginate(30);
        return view('admin.logs.activity', compact('logs'));
    }

    public function api(Request $request)
    {
        $query = ApiLog::query();

        if ($event = $request->get('event')) {
            $query->where('event', $event);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $logs = $query->latest()->paginate(30);
        return view('admin.logs.api', compact('logs'));
    }

    public function replay(ApiLog $apiLog)
    {
        $order = Order::whereHas('subscriber', fn ($q) => $q->where('bat_id', $apiLog->bat_id))->latest()->first();

        if ($order) {
            NotifyBatIdJob::dispatch($order, $apiLog->event);
            return back()->with('success', 'Notification rejouée.');
        }

        return back()->with('error', 'Commande introuvable pour ce bat-ID.');
    }

    public function documentation()
    {
        $types = SubscriptionType::online()->ordered()->with('translations')->get();
        $baseUrl = config('app.url');
        $secretConfigured = ! empty(config('batid.deeplink_secret'));

        return view('admin.api.documentation', compact('types', 'baseUrl', 'secretConfigured'));
    }

    public function generateTestToken(Request $request, DeeplinkService $deeplinkService)
    {
        $phone = $request->query('phone', config('batid.mock_phone'));
        $batId = $request->query('bat_id', config('batid.mock_batid'));
        $typeId = (int) $request->query('type_id', 1);
        $duration = (int) $request->query('duration', 12);

        if (empty(config('batid.deeplink_secret'))) {
            return back()->with('error', 'DEEPLINK_SECRET n\'est pas configuré dans le fichier .env');
        }

        $token = $deeplinkService->generateToken($phone, $batId, $typeId, $duration);
        $url = config('app.url') . '/deeplink?token=' . $token;

        return back()->with('test_token', $token)->with('test_url', $url);
    }

    public function generateTestRegisterToken(Request $request, DeeplinkService $deeplinkService)
    {
        if (empty(config('batid.deeplink_secret'))) {
            return back()->with('error', 'DEEPLINK_SECRET n\'est pas configuré dans le fichier .env');
        }

        $phone = $request->query('phone');
        if (empty($phone)) {
            $phone = '+4179' . rand(1000000, 9999999);
        }

        $batId = '@' . substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 7);

        $token = $deeplinkService->generateRegisterToken($phone, $batId);
        $url = config('app.url') . '/api/register?token=' . $token;

        return back()
            ->with('register_url', $url)
            ->with('register_phone', $phone)
            ->with('register_bat_id', $batId);
    }

    public function sendDocumentation(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'section' => 'required|in:deeplink,register,webhook,default,subscriptions',
        ]);

        $secret = config('batid.deeplink_secret', '');
        $baseUrl = config('app.url');

        $types = SubscriptionType::online()->ordered()->with('translations')->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'name' => $t->translation('fr')?->name ?? '-',
                'price' => (float) $t->price_chf,
            ])->toArray();

        Mail::to($request->input('email'))
            ->send(new ApiDocumentationMail(
                section: $request->input('section'),
                secret: $secret,
                baseUrl: $baseUrl,
                types: $types,
            ));

        AdminLogService::log('email', 'api_documentation', null, [
            'section' => $request->input('section'),
            'recipient' => $request->input('email'),
        ]);

        return back()->with('doc_email_sent', $request->input('section'));
    }
}
