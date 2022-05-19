<?php

namespace highjin\QueueMonitor\Tests\Support;

use highjin\QueueMonitor\Traits\IsMonitored;

class MonitoredFailingJob extends BaseJob
{
    use IsMonitored;

    public function handle(): void
    {
        throw new IntentionallyFailedException('Whoops');
    }
}
