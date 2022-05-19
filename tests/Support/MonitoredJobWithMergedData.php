<?php

namespace napopravku\QueueMonitor\Tests\Support;

use napopravku\QueueMonitor\Traits\IsMonitored;

class MonitoredJobWithMergedData extends BaseJob
{
    use IsMonitored;

    public function handle(): void
    {
        $this->queueData([
            'foo' => 'foo',
        ]);

        $this->queueData([
            'bar' => 'bar',
        ], true);
    }
}
