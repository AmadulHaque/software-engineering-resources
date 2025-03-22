<?php

namespace App\Services\Cache;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Services\Cache\BaseCacheService;
use Illuminate\Database\Eloquent\Collection;

class SearchCache extends BaseCacheService
{
    public function search(string $query, string $model, array $filters = []): mixed
    {
        $fields = $this->getSearchableFields($model);
        $cacheKey = $this->generateSearchKey($query, $model, $fields, $filters);
        
        return Cache::store($this->connection)->remember(
            $cacheKey,
            $this->defaultTTL,
            fn () => $this->performSearch($query, $model, $fields, $filters)
        );
    }

    protected function getSearchableFields(string $model): array
    {
        $instance = new $model();
        if (isset($instance->searchableFields) && is_array($instance->searchableFields)) {
            return $instance->searchableFields;
        }
        throw new \InvalidArgumentException("No searchable fields defined for model: {$model}");
    }

    protected function generateSearchKey(string $query, string $model, array $fields, array $filters): string
    {
        $filterString = http_build_query($filters);
        return $this->generateKey(
            sprintf('search:%s:%s:%s:%s',
                $model,
                md5($query),
                md5(implode(',', $fields)),
                md5($filterString)
            )
        );
    }

    protected function performSearch(string $query, string $model, array $fields, array $filters): Collection
    {
        $queryBuilder = $model::query();
        foreach ($fields as $field) {
            $queryBuilder->orWhere($field, 'LIKE', "%{$query}%");
        }

        if (isset($filters['category_id'])) {
            $queryBuilder->where('category_id', $filters['category_id']);
        }
        if (isset($filters['min_price'])) {
            $queryBuilder->where('price', '>=', $filters['min_price']);
        }
        if (isset($filters['max_price'])) {
            $queryBuilder->where('price', '<=', $filters['max_price']);
        }
        if (isset($filters['in_stock'])) {
            $queryBuilder->where('stock', '>', 0);
        }
        return $queryBuilder->get();
    }

    public function invalidateSearch(string $model): void
    {
        $pattern = $this->generateKey("search:{$model}:*");
        $keys = Redis::keys($pattern);
        foreach ($keys as $key) {
            Redis::del($key);
        }
    }
}