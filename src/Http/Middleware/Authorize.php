<?php

namespace Si6\Base\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Si6\Base\Exceptions\Forbidden;

class Authorize
{
    /**
     * @param $request
     * @param  Closure  $next
     * @param  mixed  ...$permissions
     * @return mixed
     * @throws Forbidden
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        if (!empty($permissions[0]) && $permissions[0] === 'or') {
            $this->authorizeOr($permissions);
        } else {
            $this->authorizeAll($permissions);
        }

        return $next($request);
    }

    /**
     * @param $permissions
     * @throws Forbidden
     */
    protected function authorizeOr($permissions)
    {
        unset($permissions[0]);
        $hasPermission = false;
        $user = Auth::user();
        foreach ($permissions as $permission) {
            if (in_array($permission, $user->getPermissions())) {
                $hasPermission = true;
                break;
            }
        }
        if (!$hasPermission) {
            $forbidden = implode('_or_', $permissions);
            throw new Forbidden($forbidden);
        }
    }

    /**
     * @param $permissions
     * @throws Forbidden
     */
    protected function authorizeAll($permissions)
    {
        $user = Auth::user();
        foreach ($permissions as $permission) {
            if (!in_array($permission, $user->getPermissions())) {
                throw new Forbidden($permission);
            }
        }
    }
}
