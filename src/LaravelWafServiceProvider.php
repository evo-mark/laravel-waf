<?php

declare(strict_types=1);

namespace EvoMark\LaravelWaf;

use EvoMark\LaravelWaf\Actions\InstallPrependFile;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelWafServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('laravel-waf')
            ->hasConfigFile('waf')
            ->hasMigrations(['laravel_waf__create_waf_rules_table'])
            ->runsMigrations()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->publishConfigFile()
                    ->endWith(fn (InstallCommand $command) => app(InstallPrependFile::class)($command));
            });
    }
}
