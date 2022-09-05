<?php

namespace Napopravku\QueueMonitor;

use Illuminate\Support\Facades\Schema;
use Napopravku\QueueMonitor\Jobs\EventMonitorJob;
use Napopravku\QueueMonitor\Data\MockResultData;
use Napopravku\QueueMonitor\Events\MockResultEvent;
use Napopravku\QueueMonitor\Models\Monitor;

class EventMonitor
{
    public static function mockResult(?array $data, ?array $errors, string $status)
    {
        if (Schema::hasTable(config('queue-monitor.table'))) {
            $job = Monitor::ordered()->first(['job_id']);

            if (!isset($job->job_id)) {
                return;
            }

            $data = new MockResultData($data, $errors, $status, $job->job_id);
            $job = new EventMonitorJob(new MockResultEvent($data));
            $job->onQueue(config('queue-monitor.queue'));
            $job->delay(1);
            dispatch($job);
        }
    }
}
