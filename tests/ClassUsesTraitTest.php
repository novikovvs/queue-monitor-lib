<?php

namespace highjin\QueueMonitor\Tests;

use highjin\QueueMonitor\Services\ClassUses;
use highjin\QueueMonitor\Tests\Support\MonitoredExtendingJob;
use highjin\QueueMonitor\Tests\Support\MonitoredJob;
use highjin\QueueMonitor\Traits\IsMonitored;

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
