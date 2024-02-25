<?php

namespace Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function setUpDatabase()
    {
        tap($this->app['db']->connection()->getSchemaBuilder(), function ($schema) {
            $schema->create('markets', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->timestamps();
            });
        });
    }
}
