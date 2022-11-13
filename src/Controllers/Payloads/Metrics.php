<?php

namespace Novikovvs\QueueMonitor\Controllers\Payloads;

final class Metrics
{
    /**
     * @var \Novikovvs\QueueMonitor\Controllers\Payloads\Metric[]
     */
    public $metrics = [];

    /**
     * @return \Novikovvs\QueueMonitor\Controllers\Payloads\Metric[]
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
