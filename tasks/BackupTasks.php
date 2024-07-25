<?php

use Crunz\Schedule;

$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . ' index.php');       
$task->dailyAt(config('app.backup.time'));

return $schedule;