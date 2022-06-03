<?php

namespace napopravku\QueueMonitor\Events;

use napopravku\QueueMonitor\Data\MockResultData;

class MockResultEvent implements EventInterface
{
    public MockResultData $data;

    public function __construct(MockResultData $data)
    {
        $this->data = $data;
    }
}
