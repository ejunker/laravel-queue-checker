<?php

namespace Schickling\QueueChecker\ErrorHandlers;

use Log;

class LogErrorHandler implements ErrorHandlerInterface
{
    public function handle($queueName, $errorCode, $message)
    {
        Log::error('Queue: ' . $queueName . ' Error Code: ' . $errorCode . '. Message: ' . $message);
    }
}
