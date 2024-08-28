<?php

namespace Startupful\WebpageManager;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Livewire\Livewire;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Startupful\WebpageManager\Resources\WebpageManagerResource;

class WebpageManagerServiceProvider extends PackageServiceProvider
{
    public static string $name = 'webpage-manager';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasRoute('web')
            ->hasMigrations(['create_webpage_manager_tables'])
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('Webpage Manager'),
            ]);
        });
    }

    public function boot()
    {
        parent::boot();
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'webpage-manager');

        // Publish Laraberg assets
        $this->publishes([
            base_path('vendor/van-ons/laraberg/public') => public_path('vendor/laraberg'),
        ], 'laraberg-assets');

        // Publish Webpage Manager assets
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/webpage-manager'),
        ], 'webpage-manager-assets');

            // Publish config file
            $this->publishes([
                __DIR__.'/../config/webpage-manager.php' => config_path('webpage-manager.php'),
            ], 'webpage-manager-config');
    }

    public function registeringPackage()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/webpage-manager.php', 'webpage-manager'
        );
    }

    protected function bootLivewireComponents(): string
    {
        return '';
    }
}