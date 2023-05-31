<?php

namespace Novikovvs\QueueMonitor\Services;

use Napopravku\Events\EsEvents\Common\EsEventSender;
use Illuminate\Contracts\Queue\Job as JobContract;
use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Carbon;
use Novikovvs\QueueMonitor\Models\Contracts\MonitorContract;
use Novikovvs\QueueMonitor\Validators\ClassValidator;
use Throwable;

class QueueMonitor
{
    private const TIMESTAMP_EXACT_FORMAT = 'Y-m-d H:i:s.u';

    public static ?EsEventSender $esEventSender = null;

    /**
     * @var bool
     */
    public static $loadMigrations = false;

    /**
     * @var \Novikovvs\QueueMonitor\Models\Contracts\MonitorContract
     */
    public static $model;

    /**
     * Get the model used to store the monitoring data.
     *
     * @return \Novikovvs\QueueMonitor\Models\Contracts\MonitorContract
     */
    public static function getModel(): MonitorContract
    {
        return new self::$model();
    }

    /**
     * Handle Job Processing.
     *
     * @param \Illuminate\Queue\Events\JobProcessing $event
     *
     * @return void
     */
    public static function handleJobProcessing(JobProcessing $event): void
    {
        self::jobStarted($event->job);
    }

    /**
     * Handle Job Processed.
     *
     * @param \Illuminate\Queue\Events\JobProcessed $event
     *
     * @return void
     */
    public static function handleJobProcessed(JobProcessed $event): void
    {
        self::jobFinished($event->job);
    }

    /**
     * Handle Job Failing.
     *
     * @param \Illuminate\Queue\Events\JobFailed $event
     *
     * @return void
     */
    public static function handleJobFailed(JobFailed $event): void
    {
        self::jobFinished($event->job, true, $event->exception);
    }

    /**
     * Handle Job Exception Occurred.
     *
     * @param \Illuminate\Queue\Events\JobExceptionOccurred $event
     *
     * @return void
     */
    public static function handleJobExceptionOccurred(JobExceptionOccurred $event): void
    {
        self::jobFinished($event->job, true, $event->exception);
    }

    /**
     * Get Job ID.
     *
     * @param \Illuminate\Contracts\Queue\Job $job
     *
     * @return string|int
     */
    public static function getJobId(JobContract $job)
    {
        if ($jobId = $job->getJobId()) {
            return $jobId;
        }

        return sha1($job->getRawBody());
    }

    /**
     * Start Queue Monitoring for Job.
     *
     * @param \Illuminate\Contracts\Queue\Job $job
     *
     * @return void
     */
    protected static function jobStarted(JobContract $job): void
    {
        if ( ! self::shouldBeMonitored($job)) {
            return;
        }

        $now = Carbon::now();

        $model = self::getModel();

        $model::query()->create([
            'job_id' => self::getJobId($job),
            'name' => $job->resolveName(),
            'queue' => $job->getQueue(),
            'started_at' => $now,
            'started_at_exact' => $now->format(self::TIMESTAMP_EXACT_FORMAT),
            'attempt' => $job->attempts(),
        ]);

        self::registerSingletonEsEventSender();
    }

    /**
     * Finish Queue Monitoring for Job.
     *
     * @param \Illuminate\Contracts\Queue\Job $job
     * @param bool $failed
     * @param \Throwable|null $exception
     *
     * @return void
     */
    protected static function jobFinished(JobContract $job, bool $failed = false, ?Throwable $exception = null): void
    {
        if ( ! self::shouldBeMonitored($job)) {
            return;
        }

        $model = self::getModel();

        $monitor = $model::query()
            ->where('job_id', self::getJobId($job))
            ->where('attempt', $job->attempts())
            ->orderByDesc('started_at')
            ->first();

        if (null === $monitor) {
            return;
        }

        /** @var MonitorContract $monitor */
        $now = Carbon::now();

        if ($startedAt = $monitor->getStartedAtExact()) {
            $timeElapsed = (float) $startedAt->diffInSeconds($now) + $startedAt->diff($now)->f;
        }

        $resolvedJob = $job->resolveName();

        $attributes = [
            'finished_at' => $now,
            'finished_at_exact' => $now->format(self::TIMESTAMP_EXACT_FORMAT),
            'time_elapsed' => $timeElapsed ?? 0.0,
            'failed' => $failed,
        ];

        if (null !== $exception) {
            $attributes += [
                'exception' => mb_strcut((string) $exception, 0, config('queue-monitor.db_max_length_exception', 4294967295)),
                'exception_class' => get_class($exception),
                'exception_message' => mb_strcut($exception->getMessage(), 0, config('queue-monitor.db_max_length_exception_message', 65535)),
            ];
        } else if (self::$esEventSender !== null) {
            $eventDto = self::$esEventSender->getLastEventDTO();

            if (!empty($eventDto)) {
                $attributes['data'] = json_encode(self::$esEventSender->getLastEventDTO()->toArray());
            }
        }

        $monitor->update($attributes);
    }

    /**
     * Determine weather the Job should be monitored, default true.
     *
     * @param \Illuminate\Contracts\Queue\Job $job
     *
     * @return bool
     */
    public static function shouldBeMonitored(JobContract $job): bool
    {
        $validator = new ClassValidator();
        return $validator->validate($job->resolveName());
    }

    public static function registerSingletonEsEventSender(): void
    {
        $app = app();

        $esEventSender = $app(EsEventSender::class);

        $app->singleton(EsEventSender::class, function () use ($esEventSender) {
            return $esEventSender;
        });

        self::$esEventSender = app(EsEventSender::class);
    }
}
