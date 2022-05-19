<?php

namespace napopravku\QueueMonitor\Tests\Support;

use napopravku\QueueMonitor\Traits\IsMonitored;

class MonitoredJobWithProgress extends BaseJob
{
    use IsMonitored;

    public $progress;

    public function __construct(int $progress)
    {
        $this->progress = $progress;
    }

    public function handle(): void
    {
        $this->queueProgress(
            $this->progress
        );
    }
}
