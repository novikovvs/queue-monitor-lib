<?php

namespace Napopravku\QueueMonitor\Tests\Support;

use Napopravku\QueueMonitor\Traits\IsMonitored;

class MonitoredJobWithData extends BaseJob
{
    use IsMonitored;

    public function handle(): void
    {
        $this->queueData([
            'foo' => 'foo',
        ]);

        $this->queueData([
            'foo' => 'bar',
        ]);
    }
}
