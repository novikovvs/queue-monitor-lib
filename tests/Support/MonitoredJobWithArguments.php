<?php

namespace Novikovvs\QueueMonitor\Tests\Support;

use Novikovvs\QueueMonitor\Traits\IsMonitored;

class MonitoredJobWithArguments extends BaseJob
{
    use IsMonitored;

    public $first;

    public function __construct(string $first)
    {
        $this->first = $first;
    }
}
