<?php

namespace App\Services\Cache;

interface CacheableInterface
{
    public function getCacheKey(): string;
    public function getCacheTTL(): int;
    
}
