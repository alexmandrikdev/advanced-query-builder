<?php

use Illuminate\Support\Facades\Cache;
use Tests\Models\User;
use Tests\QueryBuilders\UserQueryBuilder;

use function AlexMandrik\AdvancedQueryBuilder\generateCacheKey;

it('can get from the database', function () {
    User::factory(3)->create();

    expect(UserQueryBuilder::query()->get())
        ->toHaveCount(3);
});

it('can use custom methods', function () {
    User::factory()->create(['name' => 'John Doe']);
    User::factory()->create(['name' => 'Jane Doe']);

    expect(UserQueryBuilder::query()->nameContains('John')->get())
        ->toHaveCount(1)
        ->first()
        ->name
        ->toBe('John Doe');
});

it('can cache the query (get)', function () {
    User::factory(3)->create();

    $users = UserQueryBuilder::query()->cache()->get();

    $cacheKey = generateCacheKey(
        UserQueryBuilder::query(),
        'get',
        [['*']],
    );

    expect(Cache::get($cacheKey))
        ->toBe($users);
});

it('can cache the query (first)', function () {
    User::factory(3)->create();

    $user = UserQueryBuilder::query()->cache()->first();

    $cacheKey = generateCacheKey(
        UserQueryBuilder::query(),
        'first',
        [['*']],
    );

    expect(Cache::get($cacheKey))->toBe($user);
});

it('can cache the query (value)', function () {
    User::factory(3)->create();

    $marketName = UserQueryBuilder::query()->cache()->value('name');

    $cacheKey = generateCacheKey(
        UserQueryBuilder::query(),
        'value',
        ['name'],
    );

    expect(Cache::get($cacheKey))
        ->toBe($marketName);
});

it('can cache the query (pluck)', function () {
    User::factory(3)->create();

    $marketNames = UserQueryBuilder::query()->cache()->pluck('name');

    $cacheKey = generateCacheKey(
        UserQueryBuilder::query(),
        'pluck',
        ['name'],
    );

    expect(Cache::get($cacheKey))
        ->toBe($marketNames);
});

it('can cache the query (paginate)', function () {
    User::factory(3)->create();

    $users = UserQueryBuilder::query()->cache()->paginate();

    $cacheKey = generateCacheKey(
        UserQueryBuilder::query(),
        'paginate',
        [15, ['*'], 'page'],
    );

    expect(Cache::get($cacheKey))
        ->toBe($users);
});

it('can cache the query (simplePaginate)', function () {
    User::factory(3)->create();

    $users = UserQueryBuilder::query()->cache()->simplePaginate();

    $cacheKey = generateCacheKey(
        UserQueryBuilder::query(),
        'simplePaginate',
        [15, ['*'], 'page'],
    );

    expect(Cache::get($cacheKey))
        ->toBe($users);
});

it('can cache the query (count)', function () {
    User::factory(3)->create();

    $count = UserQueryBuilder::query()->cache()->count();

    $cacheKey = generateCacheKey(
        UserQueryBuilder::query(),
        'count',
        ['*'],
    );

    expect(Cache::get($cacheKey))
        ->toBe($count);
});

it('can cache the query (exists)', function () {
    User::factory(3)->create();

    $exists = UserQueryBuilder::query()->cache()->exists();

    $cacheKey = generateCacheKey(
        UserQueryBuilder::query(),
        'exists',
    );

    expect(Cache::get($cacheKey))
        ->toBe($exists);
});

it('can cache the query (max)', function () {
    User::factory(3)->create();

    $max = UserQueryBuilder::query()->cache()->max('id');

    $cacheKey = generateCacheKey(
        UserQueryBuilder::query(),
        'max',
        ['id'],
    );

    expect(Cache::get($cacheKey))
        ->toBe($max);
});

it('can cache the query (min)', function () {
    User::factory(3)->create();

    $min = UserQueryBuilder::query()->cache()->min('id');

    $cacheKey = generateCacheKey(
        UserQueryBuilder::query(),
        'min',
        ['id'],
    );

    expect(Cache::get($cacheKey))
        ->toBe($min);
});

it('can cache the query (avg)', function () {
    User::factory(3)->create();

    $avg = UserQueryBuilder::query()->cache()->avg('id');

    $cacheKey = generateCacheKey(
        UserQueryBuilder::query(),
        'avg',
        ['id'],
    );

    expect(Cache::get($cacheKey))
        ->toBe($avg);
});

it('can cache the query (sum)', function () {
    User::factory(3)->create();

    $sum = UserQueryBuilder::query()->cache()->sum('id');

    $cacheKey = generateCacheKey(
        UserQueryBuilder::query(),
        'sum',
        ['id'],
    );

    expect(Cache::get($cacheKey))
        ->toBe($sum);
});

it("doesn't cache the `get` call when the `get` is called by the other (`first`, `value`, etc.) methods", function () {
    User::factory(3)->create();

    UserQueryBuilder::query()->cache()->first();

    $cacheKey = generateCacheKey(
        UserQueryBuilder::query()->take(1),
        'get',
        [['*']],
    );

    expect(Cache::has($cacheKey))->toBeFalse();
});

it('can handle the parameters', function () {
    User::factory(3)->create();

    $users = UserQueryBuilder::query()->cache()->get(['id', 'name']);

    $cacheKey = generateCacheKey(
        UserQueryBuilder::query(),
        'get',
        [['id', 'name']],
    );

    expect(Cache::get($cacheKey))
        ->toBe($users);
});
