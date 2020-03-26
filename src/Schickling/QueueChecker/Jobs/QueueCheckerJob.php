<?php

namespace Schickling\QueueChecker\Jobs;

use Cache;

class QueueCheckerJob
{
    const CACHE_TTL = 3600;

    public function fire($task, $data)
    {
        Cache::put('queue-checker-job-value', $data['jobValue'], self::CACHE_TTL);
        $task->delete();
    }
}
