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

class EditWebpageElement extends EditRecord
{
    protected static string $resource = WebpageElementResource::class;

    public function mount(string|int $record = null): void
    {
        $headerElement = WebpageElement::firstOrCreate(
            ['type' => 'header'],
            ['name' => 'Header Settings', 'template' => '', 'code' => '', 'is_active' => true]
        );
        $footerElement = WebpageElement::firstOrCreate(
            ['type' => 'footer'],
            ['name' => 'Footer Settings', 'template' => '', 'code' => '', 'is_active' => true]
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
                    Select::make('header_template')
                        ->label('템플릿')
                        ->options(fn () => self::getTemplateOptions('headers'))
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) => $set('header_code', self::getTemplateContent('headers', $state))),
                    Textarea::make('header_code')
                        ->label('코드')
                        ->required(),
                ])
                ->aside(function () {
                    return Toggle::make('header_is_active')
                        ->label('활성화')
                        ->helperText('활성화/비활성화에 따라 홈페이지에 적용됩니다. 헤더나 푸터가 필요 없는 홈페이지의 경우 비활성화, 일반적인 홈페이지라면 활성화를 시켜주세요.')
                        ->onIcon('heroicon-s-check')
                        ->offIcon('heroicon-s-x-mark');
                }),
            Section::make('푸터')
                ->schema([
                    Select::make('footer_template')
                        ->label('템플릿')
                        ->options(fn () => self::getTemplateOptions('footers'))
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) => $set('footer_code', self::getTemplateContent('footers', $state))),
                    Textarea::make('footer_code')
                        ->label('코드')
                        ->required(),
                ])
                ->aside(function () {
                    return Toggle::make('footer_is_active')
                        ->label('활성화')
                        ->helperText('활성화/비활성화에 따라 홈페이지에 적용됩니다. 헤더나 푸터가 필요 없는 홈페이지의 경우 비활성화, 일반적인 홈페이지라면 활성화를 시켜주세요.')
                        ->onIcon('heroicon-s-check')
                        ->offIcon('heroicon-s-x-mark');
                }),
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

        WebpageElement::updateOrCreate(
            ['type' => 'header'],
            [
                'name' => $data['header_template'],
                'code' => $data['header_code'],
                'is_active' => $data['header_is_active'],
            ]
        );

        WebpageElement::updateOrCreate(
            ['type' => 'footer'],
            [
                'name' => $data['footer_template'],
                'code' => $data['footer_code'],
                'is_active' => $data['footer_is_active'],
            ]
        );

        if ($shouldSendSavedNotification) {
            $this->getSavedNotification()->send();
        }

        if ($shouldRedirect) {
            $this->redirect($this->getRedirectUrl());
        }
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
        
        if (!File::exists($path)) {
            return '';
        }
        
        return File::get($path);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}