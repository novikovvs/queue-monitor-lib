<?php

namespace Napopravku\QueueMonitor\Tests\Support;

use Napopravku\QueueMonitor\Traits\IsMonitored;

class MonitoredFailingJob extends BaseJob
{
    use IsMonitored;

    public function handle(): void
    {
        throw new IntentionallyFailedException('Whoops');
    }
}
