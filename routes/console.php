<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

Schedule::command('model:prune')->daily();
Schedule::command('driver:sync-schedules')->everyMinute();
