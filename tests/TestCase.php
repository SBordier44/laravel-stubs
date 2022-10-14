<?php

declare(strict_types=1);

namespace SBordier44\Larastubs\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use SBordier44\Larastubs\StubsServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            StubsServiceProvider::class,
        ];
    }
}
