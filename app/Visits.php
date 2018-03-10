<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Visits
{
    protected $relation;

    public function __construct($relation)
    {
        $this->relation = $relation;
    }

    public function reset()
    {
        Redis::del($this->cacheKey());

        return $this;
    }

    public function record()
    {
        Redis::incr($this->cacheKey());

        return $this;
    }

    public function count()
    {
        return Redis::get($this->cacheKey()) ?: 0;
    }

    protected function cacheKey()
    {
        return app()->environment('testing') ? "testing.threads.{$this->relation->id}.visits" : "threads.{$this->relation->id}.visits";
    }
}