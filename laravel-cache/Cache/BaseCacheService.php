<?php

namespace App\Services\Cache;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

abstract class BaseCacheService
{
    protected string $prefix;
    protected int $defaultTTL = 3600; // 1 hour

    public function __construct(protected string $connection = 'file')
    {
        $this->connection = $connection ?? config('cache.default');
        $this->prefix = static::class;
    }

    protected function generateKey(string $key): string
    {
        return sprintf('%s:%s', $this->prefix, $key);
    }

    protected function store($key, $value, $ttl = null): void
    {
        Cache::store($this->connection)->put(
            $this->generateKey($key),
            $value,
            $ttl ?? $this->defaultTTL
        );
    }

    protected function get(string $key, $default = null)
    {
        return Cache::store($this->connection)->get(
            $this->generateKey($key),
            $default
        );
    }

    protected function forget(string $key): void
    {
        Cache::store($this->connection)->forget($this->generateKey($key));
    }

    protected function tags(array $tags)
    {
        return Cache::store($this->connection)->tags($tags);
    }
}