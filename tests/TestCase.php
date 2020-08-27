<?php

namespace MatteoGgl\Linnaeus\Tests;

use Illuminate\Database\Schema\Blueprint;
use MatteoGgl\Linnaeus\LinnaeusServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        file_put_contents(__DIR__ . '/db.sqlite', null);

        $this->app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            LinnaeusServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => __DIR__ . '/db.sqlite',
            'prefix' => '',
        ]);
    }
}