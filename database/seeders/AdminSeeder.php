<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'gregory.liand@apcom.ch'],
            [
                'first_name' => 'Gregory',
                'last_name' => 'Liand',
                'password' => Hash::make('1996'),
                'role' => 'super_admin',
                'status' => 'active',
                'notify_new_order' => true,
            ]
        );
    }
}
