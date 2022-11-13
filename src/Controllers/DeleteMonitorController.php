<?php

namespace Novikovvs\QueueMonitor\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Novikovvs\QueueMonitor\Models\Monitor;

class DeleteMonitorController
{
    public function __invoke(Request $request, Monitor $monitor): RedirectResponse
    {
        $monitor->delete();

        return redirect()->route('queue-monitor::index');
    }
}
