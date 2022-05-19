<?php

namespace napopravku\QueueMonitor\Tests;

use napopravku\QueueMonitor\Services\ClassUses;
use napopravku\QueueMonitor\Tests\Support\MonitoredExtendingJob;
use napopravku\QueueMonitor\Tests\Support\MonitoredJob;
use napopravku\QueueMonitor\Traits\IsMonitored;

class ClassUsesTraitTest extends TestCase
{
    public function testUsingMonitorTrait()
    {
        $this->assertArrayHasKey(
            IsMonitored::class,
            ClassUses::classUsesRecursive(MonitoredJob::class)
        );
    }

    public function testUsingMonitorTraitExtended()
    {
        $this->assertArrayHasKey(
            IsMonitored::class,
            ClassUses::classUsesRecursive(MonitoredExtendingJob::class)
        );
    }
}
