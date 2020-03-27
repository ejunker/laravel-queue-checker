<?php

namespace Schickling\QueueChecker\ErrorHandlers;

interface ErrorHandlerInterface
{
    public function handle($queueName, $errorCode, $message);
}
