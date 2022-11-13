<?php

namespace Novikovvs\QueueMonitor\Tests\Support;

use Novikovvs\QueueMonitor\Traits\IsMonitored;

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
