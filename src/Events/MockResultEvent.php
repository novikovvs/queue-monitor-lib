<?php

namespace Novikovvs\QueueMonitor\Events;

use Novikovvs\QueueMonitor\Data\MockResultData;

class MockResultEvent implements EventInterface
{
    public MockResultData $data;

    public function __construct(MockResultData $data)
    {
        $this->data = $data;
    }
}
