<?php

namespace Napopravku\QueueMonitor\LogHandlers;

use Monolog\Logger;
use Napopravku\QueueMonitor\EventMonitor;

class QueueMonitorLogHandler
{
    private int $level;

    public function __construct($level = Logger::DEBUG)
    {
        $this->level = Logger::toMonologLevel($level);
    }

    public function isHandling(array $record): bool
    {
        return $record['level'] >= $this->level;
    }

    public function handle(array $record): bool
    {
        EventMonitor::mockResult($record['context']['data'], $record['context']['errors'], $record['context']['status']);

        return true;
    }
}