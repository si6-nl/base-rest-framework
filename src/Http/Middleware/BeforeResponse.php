<?php

namespace Si6\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use ReflectionException;
use Si6\Base\Domain\Events\AdminActivity;
use Si6\Base\Infrastructure\MicroserviceDispatcher;
use Symfony\Component\HttpFoundation\Response;

class BeforeResponse
{
    /**
     * @var MicroserviceDispatcher
     */
    private $dispatcher;

    /**
     * BeforeResponse constructor.
     *
     * @param MicroserviceDispatcher $dispatcher
     */
    public function __construct(MicroserviceDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return Response
     * @throws ReflectionException
     */
    public function handle($request, Closure $next)
    {
        /** @var Response $response */
        $response = $next($request);

        // make some changing before response
        $this->adminActivity($request, $response);

        return $response;
    }

    /**
     * @param $request
     * @param $response
     * @throws ReflectionException
     */
    protected function adminActivity($request, $response)
    {
        /** @var Request $request */

        if (!$request->header('Authorization')) {
            return;
        }

        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return;
        }

        if ($request->header('request-context') === 'internal' || !in_array(
                $request->method(),
                ['POST', 'PUT', 'PATCH', 'DELETE']
            )) {
            return;
        }

        /** @var Response $response */
        if ($response->getStatusCode() !== 200) {
            return;
        }

        $data = $request->all();
        if (isset($data['password'])) {
            unset($data['password']);
        }

        $this->dispatcher->dispatch(
            [
                new AdminActivity(
                    auth()->id(),
                    $request->method(),
                    $request->path(),
                    $data
                ),
            ]
        );
    }
}
