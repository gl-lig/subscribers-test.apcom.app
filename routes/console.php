<?php

use App\Jobs\CheckExpiringSubscriptionsJob;
use App\Jobs\CheckExpiredSubscriptionsJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new CheckExpiringSubscriptionsJob)->dailyAt('08:00');
Schedule::job(new CheckExpiredSubscriptionsJob)->dailyAt('00:05');
Schedule::command('tests:run')->dailyAt('03:00');
