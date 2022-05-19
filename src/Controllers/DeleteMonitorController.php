<?php

namespace napopravku\QueueMonitor\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use napopravku\QueueMonitor\Models\Monitor;

class DeleteMonitorController
{
    public function __invoke(Request $request, Monitor $monitor): RedirectResponse
    {
        $monitor->delete();

        return redirect()->route('queue-monitor::index');
    }
}
