<?php
namespace Veneridze\EloquentAuthorable;


use Illuminate\Database\Schema\Blueprint;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Veneridze\EloquentAuthorable\MigrationsMacros;

class ServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-eloquent-authorable')
            ->hasConfigFile('eloquent-authorable')
            ->publishesServiceProvider('ServiceProvider')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile();
            });
    }

    public function packageBooted(): void
    {
        if ($this->app->runningInConsole()) {
            Blueprint::macro('addAuthorableColumns', function ($useBigInteger = true, $usersTableName = null) {
                MigrationsMacros::addColumns($this, $useBigInteger, $usersTableName);
            });

            Blueprint::macro('dropAuthorableColumns', function () {
                MigrationsMacros::dropColumns($this);
            });
        }
    }
}
