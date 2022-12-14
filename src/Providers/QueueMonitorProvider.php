<?php

namespace Novikovvs\QueueMonitor\Providers;

use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\QueueManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Novikovvs\QueueMonitor\Models\Monitor;
use Novikovvs\QueueMonitor\Routes\QueueMonitorRoutes;
use Novikovvs\QueueMonitor\Services\QueueMonitor;

class QueueMonitorProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../migrations' => database_path('migrations'),
        ], 'migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/queue-monitor.php' => config_path('queue-monitor.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../../views' => resource_path('views/vendor/queue-monitor'),
            ], 'views');
        }

        $this->loadViewsFrom(
            __DIR__ . '/../../views',
            'queue-monitor'
        );

        /** @phpstan-ignore-next-line */
        Route::mixin(new QueueMonitorRoutes());

        /** @var QueueManager $manager */
        $manager = new QueueManager($this->app);

        if (Schema::hasTable(config('queue-monitor.table'))) {
            $manager->before(static function (JobProcessing $event) {
                $lock = \Illuminate\Support\Facades\Cache::lock('def', 300);
                while (!$lock->get()) {
                    usleep(100000);
                }

                QueueMonitor::handleJobProcessing($event);
            });

            $manager->after(static function (JobProcessed $event) {
                QueueMonitor::handleJobProcessed($event);
                \Illuminate\Support\Facades\Cache::lock('def')->forceRelease();
            });

            $manager->failing(static function (JobFailed $event) {
                QueueMonitor::handleJobFailed($event);
                \Illuminate\Support\Facades\Cache::lock('def')->forceRelease();
            });

            $manager->exceptionOccurred(static function (JobExceptionOccurred $event) {
                QueueMonitor::handleJobExceptionOccurred($event);
                \Illuminate\Support\Facades\Cache::lock('def')->forceRelease();
            });
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /** @phpstan-ignore-next-line */
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/queue-monitor.php',
            'queue-monitor'
        );

        QueueMonitor::$model = config('queue-monitor.model') ?: Monitor::class;
    }
}
