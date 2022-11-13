# Queue Monitor

This package offers monitoring like [Laravel Horizon](https://laravel.com/docs/horizon) for database queue.

## Installation

```
composer require Novikovvs/laravel-queue-monitor
```

## Configuration

Copy configuration & migration to your project:

```
php artisan vendor:publish --provider="Novikovvs\QueueMonitor\Providers\QueueMonitorProvider"  --tag=config --tag=migrations
```

Migrate the Queue Monitoring table. The table name can be configured in the config file or via the published migration.

```
php artisan migrate
```
### Lumen
Add to `bootstrap\app.php`
```php
$app->register(\Novikovvs\QueueMonitor\Providers\QueueMonitorProvider::class);
```
Create `config\queue-monitor.php` and put (*or copy from* `src\config.php`)
```php
<?php

return [
    'queue' => env('MONITOR_QUEUE', 'monitor_queue'),

    'table' => 'queue_monitor',
    'connection' => null,

    'model' => \Novikovvs\QueueMonitor\Models\Monitor::class,

    'db_max_length_exception' => 4294967295,
    'db_max_length_exception_message' => 65535,

    'ui' => [
        'per_page' => 35,
        'show_custom_data' => true,
        'allow_deletion' => true,
        'allow_purge' => true,
        'show_metrics' => true,
        'metrics_time_frame' => 14,
    ],
];

```
## Usage

To monitor a job, simply add the `Novikovvs\QueueMonitor\Traits\IsMonitored` Trait.

```php
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Novikovvs\QueueMonitor\Traits\IsMonitored; // <---

class ExampleJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use IsMonitored; // <---
}
```

**Important!** You need to implement the `Illuminate\Contracts\Queue\ShouldQueue` interface to your job class. Otherwise, Laravel will not dispatch any events containing status information for monitoring the job.

## UI

You can enable the optional UI routes by calling `Route::queueMonitor()` inside your route file, similar to the official [ui scaffolding](https://github.com/laravel/ui).

```php
Route::prefix('jobs')->group(function () {
    Route::queueMonitor();
});
```

### Routes

| Route | Action              |
| ----- | ------------------- |
| `/`   | Show the jobs table |

## Extended usage

### Progress

You can set a **progress value** (0-100) to get an estimation of a job progression.

```php
use Illuminate\Contracts\Queue\ShouldQueue;
use Novikovvs\QueueMonitor\Traits\IsMonitored;

class ExampleJob implements ShouldQueue
{
    use IsMonitored;

    public function handle()
    {
        $this->queueProgress(0);

        // Do something...

        $this->queueProgress(50);

        // Do something...

        $this->queueProgress(100);
    }
}
``` 

### Chunk progress

A common scenario for a job is iterating through large collections.

This example job loops through a large amount of users and updates it's progress value with each chunk iteration.

```php
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Novikovvs\QueueMonitor\Traits\IsMonitored;

class ChunkJob implements ShouldQueue
{
    use IsMonitored;

    public function handle()
    {
        $usersCount = User::count();

        $perChunk = 50;

        User::query()
            ->chunk($perChunk, function (Collection $users) use ($perChunk, $usersCount) {

                $this->queueProgressChunk($usersCount‚ $perChunk);

                foreach ($users as $user) {
                    // ...
                }
            });
    }
}
```

### Progress cooldown

To avoid flooding the database with rapidly repeating update queries, you can set override the `progressCooldown` method and specify a length in seconds to wait before each progress update is written to the database. Notice that cooldown will always be ignore for the values 0, 25, 50, 75 and 100.

```php
use Illuminate\Contracts\Queue\ShouldQueue;
use Novikovvs\QueueMonitor\Traits\IsMonitored;

class LazyJob implements ShouldQueue
{
    use IsMonitored;

    public function progressCooldown(): int
    {
        return 10; // Wait 10 seconds between each progress update
    }
}
``` 

### Custom data

This package also allows setting custom data in array syntax on the monitoring model.

```php
use Illuminate\Contracts\Queue\ShouldQueue;
use Novikovvs\QueueMonitor\Traits\IsMonitored;

class CustomDataJob implements ShouldQueue
{
    use IsMonitored;

    public function handle()
    {
        $this->queueData(['foo' => 'Bar']);
        
        // WARNING! This is overriding the monitoring data
        $this->queueData(['bar' => 'Foo']);

        // To preserve previous data and merge the given payload, set the $merge parameter true
        $this->queueData(['bar' => 'Foo'], true);
    }
}
``` 

In order to show custom data on UI you need to add this line under `config/queue-monitor.php`
```php
'ui' => [
    ...

    'show_custom_data' => true,

    ...
]
```

### Only keep failed jobs

You can override the `keepMonitorOnSuccess()` method to only store failed monitor entries of an executed job. This can be used if you only want to keep  failed monitors for jobs that are frequently executed but worth to monitor. Alternatively you can use Laravel's built in `failed_jobs` table.

```php
use Illuminate\Contracts\Queue\ShouldQueue;
use Novikovvs\QueueMonitor\Traits\IsMonitored;

class FrequentSucceedingJob implements ShouldQueue
{
    use IsMonitored;

    public static function keepMonitorOnSuccess(): bool
    {
        return false;
    }
}
``` 

### Retrieve processed Jobs

```php
use Novikovvs\QueueMonitor\Models\Monitor;

$job = Monitor::query()->first();

// Check the current state of a job
$job->isFinished();
$job->hasFailed();
$job->hasSucceeded();

// Exact start & finish dates with milliseconds
$job->getStartedAtExact();
$job->getFinishedAtExact();

// If the job is still running, get the estimated seconds remaining
// Notice: This requires a progress to be set
$job->getRemainingSeconds();
$job->getRemainingInterval(); // Carbon\CarbonInterval

// Retrieve any data that has been set while execution
$job->getData();

// Get the base name of the executed job
$job->getBasename();
```

### Model Scopes

```php
use Novikovvs\QueueMonitor\Models\Monitor;

// Filter by Status
Monitor::failed();
Monitor::succeeded();

// Filter by Date
Monitor::lastHour();
Monitor::today();

// Chain Scopes
Monitor::today()->failed();
```
