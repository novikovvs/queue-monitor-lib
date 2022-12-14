<?php

namespace Novikovvs\QueueMonitor\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Novikovvs\QueueMonitor\Events\EventInterface;

class EventMonitorJob implements ShouldQueue
{
    use Queueable;

    public EventInterface $event;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(EventInterface $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Dispatcher $eventDispatcher)
    {
        $eventDispatcher->dispatch($this->event);
    }
}
