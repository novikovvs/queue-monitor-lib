<?php

namespace highjin\QueueMonitor\Tests\Support;

use highjin\QueueMonitor\Traits\IsMonitored;

class MonitoredJob extends BaseJob
{
    use IsMonitored;
}
