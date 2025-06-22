<?php

namespace Zeynallow\Hashtags\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Zeynallow\Hashtags\LaravelHashtagsServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelHashtagsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Database configuration
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
} 