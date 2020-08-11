<?php

namespace Si6\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ClientPlatform
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $platform = $request->headers->get('client-platform');
        if ($platform && \Si6\Base\Enums\ClientPlatform::hasValue($platform)) {
            config(['app.platform' => $platform]);
        }

        return $next($request);
    }
}
