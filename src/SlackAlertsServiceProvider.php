<?php

namespace MwakaAmbrose\SlackAlert;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SlackAlertsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-slack-alert')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->bind('laravel-slack-alert', function () {
            return new SlackAlert();
        });
    }
}
