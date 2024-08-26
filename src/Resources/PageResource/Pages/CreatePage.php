<?php
namespace Startupful\WebpageManager\Resources\PageResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Startupful\WebpageManager\Resources\PageResource;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;
}
