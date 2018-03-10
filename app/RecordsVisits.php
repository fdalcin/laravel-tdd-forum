<?php

namespace App;

use Illuminate\Support\Facades\Redis;

trait RecordsVisits
{

    public function recordVisit()
    {
        Redis::incr($this->visitsCacheKey());

        return $this;
    }

    public function resetVisits()
    {
        Redis::del($this->visitsCacheKey());

        return $this;
    }

    public function visits()
    {
        return Redis::get($this->visitsCacheKey()) ?: 0;
    }

    protected function visitsCacheKey()
    {
        return app()->environment('testing') ? "testing.threads.{$this->id}.visits" : "threads.{$this->id}.visits";
    }
}