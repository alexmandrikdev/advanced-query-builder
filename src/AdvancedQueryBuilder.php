<?php

namespace AlexMandrik\AdvancedQueryBuilder;

use Arr;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use function basename;
use function cache;

class AdvancedQueryBuilder extends Builder
{
    protected bool $shouldCache = false;

    protected int $cacheTtl = 10 * 60;

    public static function query(): static
    {
        $tableName = str(basename(static::class))->remove('QueryBuilder')->snake()->plural();

        return (new static(DB::query()->getConnection()))
            ->from($tableName);
    }

    public function cache(): static
    {
        $this->shouldCache = true;

        return $this;

    }

    public function get($columns = ['*']): Collection
    {
        return $this->callWithCache('get', $columns);
    }

    public function first($columns = ['*']): ?object
    {
        return $this->callWithCache('first', $columns);
    }

    public function value($column): mixed
    {
        return $this->callWithCache('value', $column);
    }

    public function pluck($column, $key = null)
    {
        return $this->callWithCache('pluck', $column, $key);
    }

    public function paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null)
    {
        return $this->callWithCache('paginate', $perPage, $columns, $pageName, $page);
    }

    public function simplePaginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null)
    {
        return $this->callWithCache('simplePaginate', $perPage, $columns, $pageName, $page);
    }

    public function count($columns = '*')
    {
        return $this->callWithCache('count', $columns);
    }

    public function sum($column)
    {
        return $this->callWithCache('sum', $column);
    }

    public function avg($column)
    {
        return $this->callWithCache('avg', $column);
    }

    public function min($column)
    {
        return $this->callWithCache('min', $column);
    }

    public function max($column)
    {
        return $this->callWithCache('max', $column);
    }

    public function exists()
    {
        return $this->callWithCache('exists');
    }

    public function callWithCache(
        string $method,
        mixed $parameter1 = null,
        mixed $parameter2 = null,
        mixed $parameter3 = null,
        mixed $parameter4 = null,
    ): mixed {
        $parameters = [
            $parameter1,
            $parameter2,
            $parameter3,
            $parameter4,
        ];

        $callback = fn () => parent::{$method}(...$parameters);

        if ($this->shouldCache) {
            $this->shouldCache = false;

            $key = generateCacheKey($this, $method, Arr::whereNotNull($parameters));

            return cache()->remember(
                $key,
                $this->cacheTtl,
                $callback,
            );
        }

        return $callback();
    }
}
