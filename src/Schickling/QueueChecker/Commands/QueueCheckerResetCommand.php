<?php

namespace Schickling\QueueChecker\Commands;

use Illuminate\Console\Command;
use Cache;

class QueueCheckerResetCommand extends Command
{
    protected $name = 'queue:reset-check';

    protected $description = 'Reset values for checking queue';

    public function handle()
    {
        Cache::forget('queue-checker-job-value');
        Cache::forget('queue-checker-command-value');
    }
}
