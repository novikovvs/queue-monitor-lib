<?php

namespace napopravku\QueueMonitor;

use napopravku\QueueMonitor\Jobs\EventMonitorJob;
use napopravku\QueueMonitor\Data\MockResultData;
use napopravku\QueueMonitor\Events\MockResultEvent;
use napopravku\QueueMonitor\Models\Monitor;

class EventMonitor
{
    public static function mockResult(?array $data, ?array $errors, string $status)
    {

        $job = Monitor::ordered()->first(['job_id']);

        if (!$job->job_id){
            return;
        }

        $data = new MockResultData($data, $errors, $status, $job->job_id);
        $job = new EventMonitorJob(new MockResultEvent($data));
        $job->onQueue(config('queue-monitor.queue'));
        dispatch($job);
    }
}
