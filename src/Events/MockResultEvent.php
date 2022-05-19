<?php

namespace highjin\QueueMonitor\Events;

use highjin\QueueMonitor\Data\AbstractData;
use highjin\QueueMonitor\Data\MockResultData;
use highjin\QueueMonitor\Models\Monitor;

class MockResultEvent implements EventInterface
{
    public MockResultData $data;

    public function __construct(MockResultData $data)
    {
        $this->data = $data;
    }
}
