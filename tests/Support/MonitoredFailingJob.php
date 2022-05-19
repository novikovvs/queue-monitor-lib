<?php

namespace napopravku\QueueMonitor\Tests\Support;

use napopravku\QueueMonitor\Traits\IsMonitored;

class MonitoredFailingJob extends BaseJob
{
    use IsMonitored;

    public function handle(): void
    {
        throw new IntentionallyFailedException('Whoops');
    }
}
