<?php
namespace Startupful\WebpageManager\Resources\PageResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Startupful\WebpageManager\Resources\PageResource;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;
}
