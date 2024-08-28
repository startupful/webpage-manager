<?php
namespace Startupful\WebpageManager\Resources\PageResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Startupful\WebpageManager\Resources\PageResource;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['content'] = $data['content'] ?? [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'paragraph',
                    'content' => [
                        ['type' => 'text', 'text' => '']
                    ]
                ]
            ]
        ];

        return $data;
    }

    protected function getFormSchema(): array
    {
        return $this->getResource()::getFormSchema($this->makeForm())
            ->getSchema();
    }
}