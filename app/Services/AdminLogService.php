<?php

namespace App\Services;

use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;

class AdminLogService
{
    public static function log(string $action, string $module, ?array $before = null, ?array $after = null): void
    {
        AdminActivityLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => $action,
            'module' => $module,
            'data_before' => $before,
            'data_after' => $after,
            'ip_address' => request()->ip(),
        ]);
    }
}
