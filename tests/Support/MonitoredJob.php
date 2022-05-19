<?php

namespace napopravku\QueueMonitor\Tests\Support;

use napopravku\QueueMonitor\Traits\IsMonitored;

class MonitoredJob extends BaseJob
{
    use IsMonitored;
}
