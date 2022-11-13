<?php

namespace Novikovvs\QueueMonitor\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Novikovvs\QueueMonitor\Models\Contracts\MonitorContract;
use Novikovvs\QueueMonitor\Services\QueueMonitor;

class PurgeMonitorsController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $model = QueueMonitor::getModel();

        $model->newQuery()->each(function (MonitorContract $monitor) {
            $monitor->delete();
        }, 200);

        return redirect()->route('queue-monitor::index');
    }
}
