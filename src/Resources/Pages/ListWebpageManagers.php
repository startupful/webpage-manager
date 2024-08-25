<?php

namespace Startupful\WebpageManager\Resources\Pages;

use Startupful\WebpageManager\Resources\WebpageManagerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebpageManagers extends ListRecords
{
    protected static string $resource = WebpageManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}