<?php

namespace Si6\Base\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Si6\Base\Enums\MasterType;
use Si6\Base\Http\Queryable;
use Si6\Base\Http\ResponseTrait;
use Throwable;

class BaseController extends Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
    use ResponseTrait;
    use Queryable;

    /**
     * @param callable $callback
     * @return mixed
     * @throws Throwable
     */
    protected function transaction(callable $callback)
    {
        return DB::transaction($callback);
    }

    /**
     * @param $objects
     * @param string $key
     * @return Collection
     */
    protected function mapWithKeys($objects, $key = 'id')
    {
        if (!$objects) {
            return collect([]);
        }

        $objects = collect($objects)->mapWithKeys(function ($object) use ($key) {
            return [$object->{$key} => $object];
        });

        return $objects;
    }

    /**
     * @param array $masters
     * @return Collection
     */
    protected function mapMasterWithIdentifier(array $masters)
    {
        $types = MasterType::toSelectArray();

        $groups = collect($masters)->mapToGroups(function ($master) use ($types) {
            $key = Str::plural(Str::snake($types[$master->identifier] ?? 'undefined'));

            return [$key => $master];
        });

        return $groups->map(function ($item) {
            return $this->mapWithKeys($item, 'code');
        });
    }
}
