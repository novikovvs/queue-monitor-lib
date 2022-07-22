<?php

namespace Napopravku\QueueMonitor\Tests\Support;

use Napopravku\QueueMonitor\Traits\IsMonitored;

class MonitoredJobWithMergedDataConflicting extends BaseJob
{
    use IsMonitored;

    public function handle(): void
    {
        $this->queueData([
            'foo' => 'old',
        ]);

        $this->queueData([
            'foo' => 'new',
        ], true);
    }
}
