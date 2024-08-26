<?php

namespace Startupful\WebpageManager\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Startupful\StartupfulPlugin\Models\PluginSetting;
use Startupful\WebpageManager\Resources\MenuManagerResource\Pages;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;

class MenuManagerResource extends Resource
{
    protected static ?string $model = PluginSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationGroup = 'Webpage Manager';

    protected static ?string $navigationLabel = '메뉴 설정';

    protected static ?string $modelLabel = '메뉴 설정';

    protected static ?string $pluralModelLabel = '메뉴 설정';

    protected static ?string $breadcrumb = '메뉴 설정';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('unify_menus')
                    ->label('헤더와 푸터 메뉴 통일')
                    ->reactive(),
                Section::make('헤더 메뉴')
                    ->schema([
                        static::getMenuRepeater('header_menu'),
                    ])
                    ->hidden(fn (Get $get): bool => $get('unify_menus')),
                Section::make('푸터 메뉴')
                    ->schema([
                        static::getMenuRepeater('footer_menu'),
                    ])
                    ->hidden(fn (Get $get): bool => $get('unify_menus')),
                Section::make('통합 메뉴')
                    ->schema([
                        static::getMenuRepeater('unified_menu'),
                    ])
                    ->visible(fn (Get $get): bool => $get('unify_menus')),
                    ]);
    }

    protected static function getMenuRepeater($name)
    {
        return Repeater::make($name)
            ->schema([
                TextInput::make('label')
                    ->label('메뉴 이름')
                    ->required(),
                TextInput::make('url')
                    ->label('URL')
                    ->required(),
                Select::make('target')
                    ->label('타겟')
                    ->options([
                        '_self' => '현재 창',
                        '_blank' => '새 창',
                    ])
                    ->default('_self'),
                Repeater::make('children')
                    ->label('하위 메뉴')
                    ->schema([
                        TextInput::make('label')
                            ->label('하위 메뉴 이름')
                            ->required(),
                        TextInput::make('url')
                            ->label('URL')
                            ->required(),
                        Select::make('target')
                            ->label('타겟')
                            ->options([
                                '_self' => '현재 창',
                                '_blank' => '새 창',
                            ])
                            ->default('_self'),
                    ])
                    ->columns(3)
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
            ])
            ->columns(3)
            ->collapsible()
            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
            ->defaultItems(1);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\EditMenuManager::route('/'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('plugin_name', 'webpage-manager')->where('key', 'menu');
    }
}