<?php

namespace Startupful\WebpageManager;

use Filament\Contracts\Plugin;
use Filament\Panel;

class WebpageManagerPlugin implements Plugin
{
    public function getId(): string
    {
        return 'avatar-chat';
    }

    public function register(Panel $panel): void
    {
        $panel
        ->resources([
            Resources\WebpageManagerResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }
}