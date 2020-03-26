<?php

namespace Schickling\QueueChecker\Commands;

use Illuminate\Console\Command;
use Schickling\QueueChecker\ErrorHandlers\ErrorHandlerInterface;
use Schickling\QueueChecker\ErrorHandlers\Errors;
use Schickling\QueueChecker\Jobs\QueueCheckerJob;
use Cache;
use Queue;
use App;

class QueueCheckerCommand extends Command
{
    protected $name = 'queue:check';

    protected $description = 'Check queue is running';

    public function fire()
    {
        // TODO remove quick fix
        Queue::connection();

        if (Queue::connected()) {
            $jobValue = Cache::get('queue-checker-job-value', 0);
            $queueValue = Cache::get('queue-checker-command-value', 0);

            if ($jobValue == $queueValue) {
                $jobValue++;
                $jobValue %= 1000000;
                Queue::push(QueueCheckerJob::class, ['jobValue' => $jobValue]);
                Cache::put('queue-checker-command-value', $jobValue, QueueCheckerJob::CACHE_TTL);
            } else {
                $errorHandler = App::make(ErrorHandlerInterface::class);
                $errorHandler->handle(Errors::NOT_WORKING, 'Queue does not seem to be working.');
            }
        } else {
            $errorHandler = App::make(ErrorHandlerInterface::class);
            $errorHandler->handle(Errors::NOT_CONNECTED, 'Queue is not connected.');
        }
    }
}
