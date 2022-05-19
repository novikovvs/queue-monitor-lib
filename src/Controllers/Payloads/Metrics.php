<?php

namespace napopravku\QueueMonitor\Controllers\Payloads;

final class Metrics
{
    /**
     * @var \napopravku\QueueMonitor\Controllers\Payloads\Metric[]
     */
    public $metrics = [];

    /**
     * @return \napopravku\QueueMonitor\Controllers\Payloads\Metric[]
     */
    public function all(): array
    {
        return $this->metrics;
    }

    public function push(Metric $metric): self
    {
        $this->metrics[] = $metric;

        return $this;
    }
}
