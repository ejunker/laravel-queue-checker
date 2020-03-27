<?php

namespace Schickling\QueueChecker\Jobs;

use Cache;

class QueueCheckerJob
{
    const CACHE_TTL = 3600;

    protected $cacheKey;
    protected $jobValue;

    public function __construct($cacheKey, $jobValue)
    {
        $this->cacheKey = $cacheKey;
        $this->jobValue = $jobValue;
    }

    public function handle()
    {
        Cache::put($this->cacheKey, $this->jobValue, self::CACHE_TTL);
    }
}
