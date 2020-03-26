<?php

namespace Schickling\QueueChecker;

use Illuminate\Support\ServiceProvider;
use Schickling\QueueChecker\ErrorHandlers\ErrorHandlerInterface;
use Schickling\QueueChecker\ErrorHandlers\LogErrorHandler;

class QueueCheckerServiceProvider extends ServiceProvider
{
    /**
     * Register the binding
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ErrorHandlerInterface::class, LogErrorHandler::class);

        $this->app['queue.check'] = $this->app->share(function($app) {
            return new Commands\QueueCheckerCommand();
        });

        $this->app['queue.reset-check'] = $this->app->share(function($app) {
            return new Commands\QueueCheckerResetCommand();
        });

        $this->commands(
            'queue.check',
            'queue.reset-check'
            );
    }
}
