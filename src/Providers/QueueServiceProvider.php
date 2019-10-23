<?php

namespace Si6\Base\Providers;

use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Log;

class QueueServiceProvider extends ServiceProvider
{
  /**
     * Register queue service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::before(function (JobProcessing $event) {
            Log::info(
                'QUEUE_PROCESSING',
                [
                    $event->connectionName,
                    $event->job->resolveName(),
                    $event->job->getRawBody(),
                ]
            );
        });

        Queue::after(function (JobProcessed $event) {
            Log::info(
                'QUEUE_PROCESSED',
                [
                    $event->connectionName,
                    $event->job->resolveName(),
                    $event->job->getRawBody(),
                ]
            );
        });
    }
}