<?php

namespace highjin\QueueMonitor;

use highjin\QueueMonitor\Jobs\EventMonitorJob;
use highjin\QueueMonitor\Data\MockResultData;
use highjin\QueueMonitor\Events\MockResultEvent;
use highjin\QueueMonitor\Models\Monitor;

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
