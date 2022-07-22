<?php

namespace Napopravku\QueueMonitor\Tests\Support;

use Napopravku\QueueMonitor\Traits\IsMonitored;

class MonitoredJob extends BaseJob
{
    use IsMonitored;
}
