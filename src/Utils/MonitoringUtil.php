<?php

namespace Si6\Base\Utils;

use Illuminate\Support\Facades\Route;

class MonitoringUtil
{
    public function services($service)
    {
        Route::get("monitoring/services/$service", function () {
            // just return empty body
        });
    }
}
