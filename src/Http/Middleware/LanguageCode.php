<?php

namespace Si6\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Si6\Base\Enums\Language;

class LanguageCode
{
    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $lang = $request->headers->get('accept-language');

        if ($lang && Language::hasValue($lang)) {
            app()->setLocale($lang);
        }

        return $next($request);
    }
}
