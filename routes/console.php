<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule event status updates to run every minute for real-time accuracy
Schedule::command('events:update-statuses')->everyMinute();

// Schedule booking status updates to run every minute for real-time accuracy
Schedule::command('bookings:update-statuses')->everyMinute();
