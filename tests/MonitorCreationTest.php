<?php

namespace Novikovvs\QueueMonitor\Tests;

use Novikovvs\QueueMonitor\Models\Monitor;
use Novikovvs\QueueMonitor\Tests\Support\MonitoredBroadcastingJob;
use Novikovvs\QueueMonitor\Tests\Support\MonitoredExtendingJob;
use Novikovvs\QueueMonitor\Tests\Support\MonitoredJob;
use Novikovvs\QueueMonitor\Tests\Support\MonitoredJobWithArguments;
use Novikovvs\QueueMonitor\Tests\Support\MonitoredPartiallyKeptFailingJob;
use Novikovvs\QueueMonitor\Tests\Support\MonitoredPartiallyKeptJob;
use Novikovvs\QueueMonitor\Tests\Support\UnmonitoredJob;

class MonitorCreationTest extends TestCase
{
    public function testCreateMonitor()
    {
        $this
            ->dispatch(new MonitoredJob())
            ->assertDispatched(MonitoredJob::class)
            ->workQueue();

        $this->assertInstanceOf(Monitor::class, $monitor = Monitor::query()->first());
        $this->assertEquals(MonitoredJob::class, $monitor->name);
    }

    public function testCreateMonitorFromExtending()
    {
        $this
            ->dispatch(new MonitoredExtendingJob())
            ->assertDispatched(MonitoredExtendingJob::class)
            ->workQueue();

        $this->assertInstanceOf(Monitor::class, $monitor = Monitor::query()->first());
        $this->assertEquals(MonitoredExtendingJob::class, $monitor->name);
    }

    public function testDontCreateMonitor()
    {
        $this
            ->dispatch(new UnmonitoredJob())
            ->assertDispatched(UnmonitoredJob::class)
            ->workQueue();

        $this->assertCount(0, Monitor::all());
    }

    public function testDontKeepSuccessfulMonitor()
    {
        $this
            ->dispatch(new MonitoredPartiallyKeptJob())
            ->assertDispatched(MonitoredPartiallyKeptJob::class)
            ->workQueue();

        $this->assertCount(0, Monitor::all());
    }

    public function testDontKeepSuccessfulMonitorFailing()
    {
        $this
            ->dispatch(new MonitoredPartiallyKeptFailingJob())
            ->assertDispatched(MonitoredPartiallyKeptFailingJob::class)
            ->workQueue();

        $this->assertInstanceOf(Monitor::class, $monitor = Monitor::query()->first());
        $this->assertEquals(MonitoredPartiallyKeptFailingJob::class, $monitor->name);
    }

    public function testBroadcastingJob()
    {
        $this
            ->dispatch(new MonitoredBroadcastingJob())
            ->assertDispatched(MonitoredBroadcastingJob::class)
            ->workQueue();

        $this->assertInstanceOf(Monitor::class, $monitor = Monitor::query()->first());
        $this->assertEquals(MonitoredBroadcastingJob::class, $monitor->name);
    }

    public function testDispatchingJobViaDispatchableTrait()
    {
        MonitoredJob::dispatch();

        $this->assertDispatched(MonitoredJob::class);
        $this->workQueue();

        $this->assertInstanceOf(Monitor::class, $monitor = Monitor::query()->first());
        $this->assertEquals(MonitoredJob::class, $monitor->name);
    }

    public function testDispatchingJobViaDispatchableTraitWithArguments()
    {
        MonitoredJobWithArguments::dispatch('foo');

        $this->assertDispatched(MonitoredJobWithArguments::class);
        $this->workQueue();

        $this->assertInstanceOf(Monitor::class, $monitor = Monitor::query()->first());
        $this->assertEquals(MonitoredJobWithArguments::class, $monitor->name);
    }
}
