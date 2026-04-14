<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AdminLogService;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = [
            'cgv_url' => Setting::get('cgv_url', 'https://bat-id.ch/terms'),
            'expiry_notification_days' => Setting::get('expiry_notification_days', '30'),
            'admin_session_timeout' => Setting::get('admin_session_timeout', '120'),
            'mail_from_address' => Setting::get('mail_from_address', 'subscribers@bat-i.ch'),
            'maintenance_mode' => Setting::get('maintenance_mode', 'false'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $fields = ['cgv_url', 'expiry_notification_days', 'admin_session_timeout', 'mail_from_address', 'maintenance_mode'];

        $before = [];
        $after = [];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $before[$field] = Setting::get($field);
                $value = $field === 'maintenance_mode' ? ($request->boolean($field) ? 'true' : 'false') : $request->input($field);
                Setting::set($field, $value);
                $after[$field] = $value;
            }
        }

        AdminLogService::log('update', 'settings', $before, $after);

        return back()->with('success', 'Paramètres sauvegardés.');
    }
}
