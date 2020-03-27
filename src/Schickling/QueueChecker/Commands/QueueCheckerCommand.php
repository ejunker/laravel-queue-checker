<?php

namespace Schickling\QueueChecker\Commands;

use Illuminate\Console\Command;
use Schickling\QueueChecker\ErrorHandlers\ErrorHandlerInterface;
use Schickling\QueueChecker\ErrorHandlers\Errors;
use Schickling\QueueChecker\Jobs\QueueCheckerJob;
use Symfony\Component\Console\Input\InputArgument;
use Cache;
use Config;
use Queue;
use App;

class QueueCheckerCommand extends Command
{
    protected $name = 'queue:check';

    protected $description = 'Check queue is running';

    public function handle()
    {
        $defaultQueue = Config::get('queue.connections.' . Config::get('queue.default') . '.queue');
        $queueName = $this->argument('queue', $defaultQueue);
        $jobCacheKey = 'queue-checker-job-value:'.$queueName;
        $commandCacheKey = 'queue-checker-command-value:'.$queueName;

        Queue::connection();
        if (!Queue::connected()) {
            return $this->handleError($queueName, Errors::NOT_CONNECTED, 'Queue is not connected.');
        }

        $jobValue = Cache::get($jobCacheKey, 0);
        $commandValue = Cache::get($commandCacheKey, 0);
        if ($jobValue != $commandValue) {
            return $this->handleError($queueName, Errors::NOT_WORKING, 'Queue does not seem to be working.');
        }

        $jobValue++;
        $jobValue %= 1000000;
        Queue::pushOn($queueName, new QueueCheckerJob($jobCacheKey, $jobValue));
        Cache::put($commandCacheKey, $jobValue, QueueCheckerJob::CACHE_TTL);
    }

    protected function handleError($queueName, $errorCode, $message)
    {
        $errorHandler = App::make(ErrorHandlerInterface::class);
        $errorHandler->handle($queueName, $errorCode, $message);
    }

    protected function getArguments()
    {
        return [
            [
                'queue',
                InputArgument::OPTIONAL,
                "Queue to queue a check for"
                . " (default is the application's default queue)",
                null,
            ],
        ];
    }
}
