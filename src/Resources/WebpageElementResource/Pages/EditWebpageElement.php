<?php

namespace Startupful\WebpageManager\Resources\WebpageElementResource\Pages;

use Startupful\WebpageManager\Resources\WebpageElementResource;
use Filament\Resources\Pages\EditRecord;
use Startupful\WebpageManager\Models\WebpageElement;
use Illuminate\Support\Facades\DB;

class EditWebpageElement extends EditRecord
{
    protected static string $resource = WebpageElementResource::class;

    public function mount($record = null): void
    {
        $elements = WebpageElement::all();

        if ($elements->isEmpty()) {
            $defaultHeaderContent = $this->getDefaultContentFromJson('header');
            $defaultFooterContent = $this->getDefaultContentFromJson('footer');
            WebpageElement::create([
                'type' => 'header',
                'name' => 'Default Header',
                'content' => $defaultHeaderContent,
                'is_active' => true,
            ]);
            WebpageElement::create([
                'type' => 'footer',
                'name' => 'Default Footer',
                'content' => $defaultFooterContent,
                'is_active' => true,
            ]);
            $elements = WebpageElement::all();
        }

        $this->record = $elements->where('is_active', true)->first() ?? $elements->first();
        $this->form->fill($this->record->attributesToArray());
    }

    private function getDefaultContentFromJson(string $type): string
    {
        $path = $type === 'header' 
            ? __DIR__ . '/../../../../../resources/views/headers.json'
            : __DIR__ . '/../../../../../resources/views/footers.json';
        $json = file_get_contents($path);
        $elements = json_decode($json, true);

        foreach ($elements as $element) {
            if ($element['code'] === 'default') {
                return json_encode($element['content']);
            }
        }

        throw new \Exception("Default {$type} not found in JSON file");
    }
}