<?php

namespace napopravku\QueueMonitor\Events;

use napopravku\QueueMonitor\Data\AbstractData;
use napopravku\QueueMonitor\Data\MockResultData;
use napopravku\QueueMonitor\Models\Monitor;

class MockResultEvent implements EventInterface
{
    public MockResultData $data;

    public function __construct(MockResultData $data)
    {
        $this->data = $data;
    }
}
