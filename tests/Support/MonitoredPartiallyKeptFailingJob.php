<?php

namespace Napopravku\QueueMonitor\Tests\Support;

use Napopravku\QueueMonitor\Traits\IsMonitored;

class MonitoredPartiallyKeptFailingJob extends BaseJob
{
    use IsMonitored;

    public static function keepMonitorOnSuccess(): bool
    {
        return false;
    }

    public function handle(): void
    {
        throw new IntentionallyFailedException('Whoops');
    }
}
