<?php

namespace Si6\Base\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Si6\Base\Exceptions\Handler;
use Si6\Base\Http\Middleware\Authenticate;
use Si6\Base\Http\Middleware\Authorize;
use Si6\Base\Http\Middleware\BeforeResponse;
use Si6\Base\Http\Middleware\CheckForMaintenanceMode;
use Si6\Base\Http\Middleware\TrimStrings;
use Si6\Base\Http\Middleware\TrustProxies;
use Si6\Base\Http\Middleware\Unacceptable;
use Si6\Base\Http\Middleware\Unsupported;
use Si6\Base\Http\Middleware\Versioning;

class Si6BaseServiceProvider extends ServiceProvider
{
    /**
     * @throws BindingResolutionException
     */
    public function register()
    {
        $this->registerGlobalMiddleware();
        $this->registerMicroservicesDependence();
        $this->registerAuthProvider();
        $this->registerLogProvider();
        $this->registerStorageProvider();
        $this->registerExternalService();
        $this->registerQueueService();
    }

    /**
     * @throws BindingResolutionException
     */
    protected function registerGlobalMiddleware()
    {
        $kernel = $this->app->make(Kernel::class);

        $kernel->prependMiddleware(TrimStrings::class);
        $kernel->prependMiddleware(CheckForMaintenanceMode::class);
        $kernel->prependMiddleware(TrustProxies::class);
        $kernel->prependMiddleware(ConvertEmptyStringsToNull::class);

        $kernel->prependMiddleware(Unsupported::class);
        $kernel->prependMiddleware(Unacceptable::class);
        $kernel->prependMiddleware(BeforeResponse::class);
    }

    protected function registerMicroservicesDependence()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/microservices.php', 'microservices');
        $this->app->bind(ClientInterface::class, function ($app, $options) {
            return new Client($options);
        });
    }

    protected function registerAuthProvider()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/auth.php', 'auth');
        $this->app->register(AuthServiceProvider::class);
    }

    protected function registerLogProvider()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/logging.php', 'logging');
    }

    protected function registerStorageProvider()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/filesystems.php', 'filesystems');
    }

    protected function registerExternalService()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/external.php', 'external');
    }

    protected function registerQueueService()
    {
        $this->app->register(QueueServiceProvider::class);
    }

    /**
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('versioning', Versioning::class);
        $router->aliasMiddleware('auth', Authenticate::class);
        $router->aliasMiddleware('authorize', Authorize::class);
    }
}
