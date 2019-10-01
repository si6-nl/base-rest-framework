<?php

namespace Si6\Base\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Si6\Base\Http\Queryable;
use Si6\Base\Http\ResponseTrait;

class BaseController extends Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
    use ResponseTrait;
    use Queryable;

    protected function transaction(callable $callback)
    {
        return DB::transaction($callback);
    }

    protected function mapWithKeys($objects, $key = 'id')
    {
        if (!$objects) {
            return [];
        }

        $objects = collect($objects)->mapWithKeys(function ($object) {
            return [$object->{$key} => $object];
        });

        return $objects;
    }
}
