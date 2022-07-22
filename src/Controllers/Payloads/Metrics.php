<?php

namespace Napopravku\QueueMonitor\Controllers\Payloads;

final class Metrics
{
    /**
     * @var \Napopravku\QueueMonitor\Controllers\Payloads\Metric[]
     */
    public $metrics = [];

    /**
     * @return \Napopravku\QueueMonitor\Controllers\Payloads\Metric[]
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
