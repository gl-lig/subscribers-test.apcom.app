<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'cgv_url' => 'https://bat-id.ch/terms',
            'expiry_notification_days' => '30',
            'admin_session_timeout' => '120',
            'mail_from_address' => 'subscribers@bat-i.ch',
            'maintenance_mode' => 'false',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
