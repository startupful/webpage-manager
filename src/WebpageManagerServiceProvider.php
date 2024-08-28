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
    public static string $name = 'webpage manager';

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
        $this->mergeConfigFrom(
            __DIR__.'/../config/webpage-manager.php', 'webpage-manager'
        );
        $this->publishes([
            base_path('vendor/van-ons/laraberg/public') => public_path('vendor/laraberg'),
        ], 'laraberg-assets');
    }

    protected function bootLivewireComponents(): string
    {
        return '';
    }
}