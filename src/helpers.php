<?php

namespace AlexMandrik\AdvancedQueryBuilder;

use Illuminate\Database\Query\Builder;

function generateCacheKey(Builder $query, string $method, mixed $parameters = []): string
{
    return json_encode([
        'method' => $method,
        'parameters' => $parameters,
        'sql' => $query->toSql(),
        'bindings' => $query->getBindings(),
    ]);
}
