<?php

namespace Startupful\WebpageManager\Resources\WebpageElementResource\Pages;

use Startupful\WebpageManager\Resources\WebpageElementResource;
use Filament\Resources\Pages\EditRecord;
use Startupful\WebpageManager\Models\WebpageElement;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditWebpageElement extends EditRecord
{
    protected static string $resource = WebpageElementResource::class;

    public function mount(string|int $record = null): void
    {
        $headerElement = WebpageElement::firstOrCreate(
            ['type' => 'header'],
            [
                'name' => 'default', 
                'code' => $this->getTemplateContent('header', 'default'),
                'is_active' => true
            ]
        );
        $footerElement = WebpageElement::firstOrCreate(
            ['type' => 'footer'],
            [
                'name' => 'default', 
                'code' => $this->getTemplateContent('footer', 'default'),
                'is_active' => true
            ]
        );

        parent::mount($headerElement->id);

        $this->form->fill([
            'header_template' => $headerElement->name,
            'header_code' => $headerElement->code,
            'header_is_active' => $headerElement->is_active,
            'footer_template' => $footerElement->name,
            'footer_code' => $footerElement->code,
            'footer_is_active' => $footerElement->is_active,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('헤더')
                ->schema([
                    Toggle::make('header_is_active')
                        ->label('활성화')
                        ->helperText('활성화/비활성화에 따라 홈페이지에 적용됩니다. 헤더나 푸터가 필요 없는 홈페이지의 경우 비활성화, 일반적인 홈페이지라면 활성화를 시켜주세요.')
                        ->onIcon('heroicon-s-check')
                        ->offIcon('heroicon-s-x-mark')
                        ->reactive(),
                    Select::make('header_template')
                        ->label('템플릿')
                        ->options(fn () => self::getTemplateOptions('header'))
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state !== 'CUSTOM') {
                                $code = $this->getTemplateContent('header', $state);
                                $set('header_code', $code);
                            }
                        })
                        ->visible(fn (callable $get) => $get('header_is_active')),
                    Textarea::make('header_code')
                        ->label('코드')
                        ->required()
                        ->visible(fn (callable $get) => $get('header_is_active') && $get('header_template') === 'CUSTOM'),
                ])
                ->columnSpan('full'),
            Section::make('푸터')
                ->schema([
                    Toggle::make('footer_is_active')
                        ->label('활성화')
                        ->helperText('활성화/비활성화에 따라 홈페이지에 적용됩니다. 헤더나 푸터가 필요 없는 홈페이지의 경우 비활성화, 일반적인 홈페이지라면 활성화를 시켜주세요.')
                        ->onIcon('heroicon-s-check')
                        ->offIcon('heroicon-s-x-mark')
                        ->reactive(),
                    Select::make('footer_template')
                        ->label('템플릿')
                        ->options(fn () => self::getTemplateOptions('footer'))
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state !== 'CUSTOM') {
                                $code = $this->getTemplateContent('footer', $state);
                                $set('footer_code', $code);
                            }
                        })
                        ->visible(fn (callable $get) => $get('footer_is_active')),
                    Textarea::make('footer_code')
                        ->label('코드')
                        ->required()
                        ->visible(fn (callable $get) => $get('footer_is_active') && $get('footer_template') === 'CUSTOM'),
                ])
                ->columnSpan('full'),
        ];
    }

    public function getTitle(): string
    {
        return '헤더/푸터 설정';
    }

    public function getBreadcrumbs(): array
    {
        return []; // 빈 배열을 반환하여 브레드크럼을 숨깁니다.
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $data = $this->form->getState();

        Log::info('Form data before save:', $data);

        DB::transaction(function () use ($data) {
            $this->updateElement('header', $data);
            $this->updateElement('footer', $data);
        });

        if ($shouldSendSavedNotification) {
            $this->getSavedNotification()->send();
        }

        if ($shouldRedirect) {
            $this->redirect($this->getRedirectUrl());
        }
    }

    private function updateElement(string $type, array $data): void
    {
        $isActive = $data["{$type}_is_active"] ?? false;
        $name = $data["{$type}_template"] ?? 'default';
        $code = $data["{$type}_code"] ?? '';

        // If the code is empty, fetch it from the template
        if (empty($code)) {
            $code = $this->getTemplateContent($type, $name);
        }

        Log::info("Updating {$type} element:", [
            'is_active' => $isActive,
            'name' => $name,
            'code' => $code,
        ]);

        $element = WebpageElement::firstOrNew(['type' => $type]);

        $element->name = $name;
        $element->code = $code;
        $element->is_active = $isActive;

        $element->save();

        Log::info("{$type} element saved:", $element->toArray());
    }

    public static function getTemplateOptions(string $type): array
    {
        $path = __DIR__ . "/../../../../resources/views/templates/{$type}";
        $files = File::files($path);

        return collect($files)->mapWithKeys(function ($file) {
            $name = $file->getFilenameWithoutExtension();
            return [$name => ucfirst($name)];
        })->toArray();
    }

    public static function getTemplateContent(string $type, string $name): string
    {
        $path = __DIR__ . "/../../../../resources/views/templates/{$type}/{$name}.php";

        Log::info("Getting template content for {$type}/{$name}", ['path' => $path]);

        if (!File::exists($path)) {
            Log::warning("Template file not found: {$path}");
            return '';
        }

        $content = File::get($path);
        Log::info("Template content retrieved", ['content' => $content]);

        return $content;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function getMenuData()
    {
        $menuSetting = PluginSetting::where('plugin_name', 'webpage-manager')
            ->where('key', 'menu')
            ->first();

        if (!$menuSetting) {
            return [
                'unify_menus' => false,
                'header_menu' => [],
                'footer_menu' => [],
                'unified_menu' => [],
            ];
        }

        $menuData = json_decode($menuSetting->value, true);

        if ($menuData['unify_menus']) {
            return [
                'headerMenuData' => $menuData['unified_menu'],
                'footerMenuData' => $menuData['unified_menu'],
            ];
        } else {
            return [
                'headerMenuData' => $menuData['header_menu'],
                'footerMenuData' => $menuData['footer_menu'],
            ];
        }
    }
}