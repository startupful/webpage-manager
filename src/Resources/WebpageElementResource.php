<?php

namespace Startupful\WebpageManager\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Startupful\WebpageManager\Models\WebpageElement;
use Startupful\WebpageManager\Resources\WebpageElementResource\Pages;

class WebpageElementResource extends Resource
{
    protected static ?string $model = WebpageElement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Webpage Manager';

    protected static ?string $navigationLabel = '헤더/푸터 설정';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('헤더')
                    ->schema([
                        Forms\Components\Select::make('header_template')
                            ->label('템플릿')
                            ->options(fn () => Pages\EditWebpageElement::getTemplateOptions('headers'))
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('header_code', Pages\EditWebpageElement::getTemplateContent('headers', $state)))
                            ->required(),
                        Forms\Components\Textarea::make('header_code')
                            ->label('코드')
                            ->required(),
                        Forms\Components\Toggle::make('header_is_active')
                            ->label('활성화')
                            ->helperText('활성화/비활성화에 따라 홈페이지에 적용됩니다. 헤더나 푸터가 필요 없는 홈페이지의 경우 비활성화, 일반적인 홈페이지라면 활성화를 시켜주세요.')
                            ->onIcon('heroicon-s-check')
                            ->offIcon('heroicon-s-x-mark'),
                    ])
                    ->extraAttributes(['class' => 'fi-section-header-wrapper']),
                Forms\Components\Section::make('푸터')
                    ->schema([
                        Forms\Components\Select::make('footer_template')
                            ->label('템플릿')
                            ->options(fn () => Pages\EditWebpageElement::getTemplateOptions('footers'))
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('footer_code', Pages\EditWebpageElement::getTemplateContent('footers', $state)))
                            ->required(),
                        Forms\Components\Textarea::make('footer_code')
                            ->label('코드')
                            ->required(),
                        Forms\Components\Toggle::make('footer_is_active')
                            ->label('활성화')
                            ->helperText('활성화/비활성화에 따라 홈페이지에 적용됩니다. 헤더나 푸터가 필요 없는 홈페이지의 경우 비활성화, 일반적인 홈페이지라면 활성화를 시켜주세요.')
                            ->onIcon('heroicon-s-check')
                            ->offIcon('heroicon-s-x-mark'),
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
}