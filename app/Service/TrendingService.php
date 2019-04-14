<?php

namespace App\Service;

use App\Thread;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class TrendingService
{
    public function get()
    {
        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));;
    }

    public function increment(Thread $thread)
    {
        if (! $this->checkCanIncrement()) return;

        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
    }

    public function clear()
    {
        Redis::del($this->cacheKey());
    }

    public function checkCanIncrement()
    {
        $ip = request()->ip();

        if (Cache::has("view-{$ip}")) return false;

        Cache::put("view-{$ip}", 1, '1');

        return true;
    }

    protected function cacheKey()
    {
        return app()->environment('testing') ? 'testing_trending_threads' : 'trending_threads';
    }
}