<?php

namespace Novikovvs\QueueMonitor\Tests\Support;

use Novikovvs\QueueMonitor\Traits\IsMonitored;

class MonitoredFailingJob extends BaseJob
{
    use IsMonitored;

    public function handle(): void
    {
        throw new IntentionallyFailedException('Whoops');
    }
}
