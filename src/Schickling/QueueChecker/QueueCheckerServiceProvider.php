<?php

namespace Schickling\QueueChecker;

use Illuminate\Support\ServiceProvider;
use Schickling\QueueChecker\Commands\QueueCheckerCommand;
use Schickling\QueueChecker\Commands\QueueCheckerResetCommand;
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
        if($this->app->runningInConsole()) {
            $this->app->bind(ErrorHandlerInterface::class, LogErrorHandler::class);

            $this->commands([
                QueueCheckerCommand::class,
                QueueCheckerResetCommand::class,
            ]);
        }
    }
}
