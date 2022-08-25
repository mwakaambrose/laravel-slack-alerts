<?php

namespace MwakaAmbrose\SlackAlert\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use MwakaAmbrose\SlackAlert\SlackAlertsServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SlackAlertsServiceProvider::class,
        ];
    }
}
