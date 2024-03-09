<?php

use function AlexMandrik\AdvancedQueryBuilder\generateCacheKey;

it('can generate a cache key', function () {
    expect(generateCacheKey(
        DB::table('users')->where('name', 'John')->where('age', '>', 18),
        'get',
        [['id', 'name', 'age']],
    ))->toBe(json_encode([
        'method' => 'get',
        'parameters' => [['id', 'name', 'age']],
        'sql' => 'select * from "users" where "name" = ? and "age" > ?',
        'bindings' => ['John', 18],
    ]));
});
