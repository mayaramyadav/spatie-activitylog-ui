<?php

namespace Mayaram\SpatieActivitylogUi\Tests;

use Mayaram\SpatieActivitylogUi\SpatieActivitylogUiServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SpatieActivitylogUiServiceProvider::class,
        ];
    }
}
