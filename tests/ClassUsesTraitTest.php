<?php

namespace Napopravku\QueueMonitor\Tests;

use Napopravku\QueueMonitor\Services\ClassUses;
use Napopravku\QueueMonitor\Tests\Support\MonitoredExtendingJob;
use Napopravku\QueueMonitor\Tests\Support\MonitoredJob;
use Napopravku\QueueMonitor\Traits\IsMonitored;

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
