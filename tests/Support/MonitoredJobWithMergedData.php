<?php

namespace highjin\QueueMonitor\Tests\Support;

use highjin\QueueMonitor\Traits\IsMonitored;

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
