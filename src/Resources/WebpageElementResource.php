<?php

namespace Startupful\WebpageManager\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Startupful\WebpageManager\Models\WebpageElement;
use Startupful\WebpageManager\Resources\WebpageElementResource\Pages;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Startupful\StartupfulPlugin\Models\PluginSetting;

class WebpageElementResource extends Resource
{
    protected static ?string $model = WebpageElement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Webpage Manager';

    protected static ?string $navigationLabel = '헤더/푸터 설정';

    public static function getTailwindCdn(): string
    {
        return '<script src="https://cdn.tailwindcss.com"></script>';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('헤더')
                ->schema([
                    Forms\Components\Toggle::make('header_is_active')
                        ->label('활성화')
                        ->helperText('활성화/비활성화에 따라 홈페이지에 적용됩니다. 헤더나 푸터가 필요 없는 홈페이지의 경우 비활성화, 일반적인 홈페이지라면 활성화를 시켜주세요.')
                        ->onIcon('heroicon-s-check')
                        ->offIcon('heroicon-s-x-mark')
                        ->reactive(),
                    Forms\Components\Select::make('header_template')
                        ->label('템플릿')
                        ->options(function () {
                            $options = Pages\EditWebpageElement::getTemplateOptions('header');
                            Log::info('Header Template Options:', ['options' => $options]);
                            return array_merge(['CUSTOM' => 'Custom'], $options);
                        })
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            Log::info('Header Template Selected:', ['state' => $state]);
                            if ($state !== 'CUSTOM') {
                                $code = Pages\EditWebpageElement::getTemplateContent('header', $state);
                                Log::info('Header Template Content:', ['code' => $code]);
                                $set('header_code', $code);
                            }
                        })
                        ->required()
                        ->visible(fn (callable $get) => $get('header_is_active')),
                    Forms\Components\Textarea::make('header_code')
                        ->label('코드')
                        ->required()
                        ->visible(fn (callable $get) => $get('header_is_active') && $get('header_template') === 'CUSTOM')
                        ->reactive()
                        ->afterStateUpdated(function ($state) {
                            Log::info('Header Code Updated:', ['code' => $state]);
                        }),
                    Forms\Components\View::make('webpage-manager::components.header-preview')
                        ->visible(fn (callable $get) => $get('header_is_active') && $get('header_template') !== 'CUSTOM'),
                ])
                ->extraAttributes(['class' => 'fi-section-header-wrapper']),
            Forms\Components\Section::make('푸터')
                ->schema([
                    Forms\Components\Toggle::make('footer_is_active')
                        ->label('활성화')
                        ->helperText('활성화/비활성화에 따라 홈페이지에 적용됩니다. 헤더나 푸터가 필요 없는 홈페이지의 경우 비활성화, 일반적인 홈페이지라면 활성화를 시켜주세요.')
                        ->onIcon('heroicon-s-check')
                        ->offIcon('heroicon-s-x-mark')
                        ->reactive(),
                    Forms\Components\Select::make('footer_template')
                        ->label('템플릿')
                        ->options(function () {
                            $options = Pages\EditWebpageElement::getTemplateOptions('footer');
                            return array_merge(['CUSTOM' => 'Custom'], $options);
                        })
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state !== 'CUSTOM') {
                                $code = Pages\EditWebpageElement::getTemplateContent('footer', $state);
                                $set('footer_code', $code);
                            }
                        })
                        ->required()
                        ->visible(fn (callable $get) => $get('footer_is_active')),
                    Forms\Components\Textarea::make('footer_code')
                        ->label('코드')
                        ->required()
                        ->visible(fn (callable $get) => $get('footer_is_active') && $get('footer_template') === 'CUSTOM')
                        ->reactive(),
                    Forms\Components\View::make('webpage-manager::components.footer-preview')
                        ->visible(fn (callable $get) => $get('footer_is_active') && $get('footer_template') !== 'CUSTOM'),
                ])
                ->extraAttributes(['class' => 'fi-section-header-wrapper']),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\EditWebpageElement::route('/'),
        ];
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

    public function getHeaderCodeAttribute($value)
    {
        return $value ?? '';
    }

    public function getFooterCodeAttribute($value)
    {
        return $value ?? '';
    }
    
}