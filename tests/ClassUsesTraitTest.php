<?php

namespace Novikovvs\QueueMonitor\Tests;

use Novikovvs\QueueMonitor\Services\ClassUses;
use Novikovvs\QueueMonitor\Tests\Support\MonitoredExtendingJob;
use Novikovvs\QueueMonitor\Tests\Support\MonitoredJob;
use Novikovvs\QueueMonitor\Traits\IsMonitored;

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
