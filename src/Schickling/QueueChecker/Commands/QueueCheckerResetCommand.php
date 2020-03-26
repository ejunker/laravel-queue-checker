<?php

namespace Schickling\QueueChecker\Commands;

use Illuminate\Console\Command;
use Schickling\QueueChecker\Jobs\QueueCheckerJob;
use Cache;

class QueueCheckerResetCommand extends Command
{
    protected $name = 'queue:reset-check';

    protected $description = 'Reset values for checking queue';

    public function fire()
    {
        Cache::put('queue-checker-job-value', 0, QueueCheckerJob::CACHE_TTL);
        Cache::put('queue-checker-command-value', 0, QueueCheckerJob::CACHE_TTL);
    }
}
