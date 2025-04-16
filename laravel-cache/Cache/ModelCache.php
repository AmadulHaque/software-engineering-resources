<?php

namespace App\Services\Cache;

use App\Services\Cache\BaseCacheService;
use App\Services\Cache\CacheableInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ModelCache extends BaseCacheService
{
    public function remember(CacheableInterface $model): mixed
    {
        $key = $model->getCacheKey();
        $ttl = $model->getCacheTTL();
        
        return Cache::store($this->connection)->remember(
            $this->generateKey($key),
            $ttl,
            fn () => $model
        );
    }

    public function invalidate(CacheableInterface $model): void
    {
        $this->forget($model->getCacheKey());
    }

    public function rememberCollection(string $key, Collection $collection, int $ttl = null): Collection
    {
        return Cache::store($this->connection)->remember(
            $this->generateKey($key),
            $ttl ?? $this->defaultTTL,
            fn () => $collection
        );
    }
}