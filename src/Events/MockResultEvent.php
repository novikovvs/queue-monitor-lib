<?php

namespace Napopravku\QueueMonitor\Events;

use Napopravku\QueueMonitor\Data\MockResultData;

class MockResultEvent implements EventInterface
{
    public MockResultData $data;

    public function __construct(MockResultData $data)
    {
        $this->data = $data;
    }
}
