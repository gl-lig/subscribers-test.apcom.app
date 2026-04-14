<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Mail\NewOrderNotification;
use App\Models\Admin;
use Illuminate\Support\Facades\Mail;

class NotifyAdminsNewOrder
{
    public function handle(OrderCompleted $event): void
    {
        $admins = Admin::notifiable()->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->queue(new NewOrderNotification($event->order));
        }
    }
}
