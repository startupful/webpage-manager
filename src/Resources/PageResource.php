<?php
namespace Startupful\WebpageManager\Resources;

use Startupful\WebpageManager\Resources\PageResource\Pages;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Startupful\WebpageManager\Models\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use FilamentTiptapEditor\TiptapEditor;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Webpage Manager';

    protected static ?string $navigationLabel = '페이지 설정';

    protected static ?string $modelLabel = '페이지 설정';

    protected static ?string $pluralModelLabel = '페이지 설정';

    protected static ?string $breadcrumb = '페이지 설정';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(Page::class, 'slug', ignoreRecord: true)
                    ->maxLength(255),
                TiptapEditor::make('content')
                    ->label('Content')
                    ->profile('default')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options([
                        'page' => 'Page',
                        'post' => 'Post',
                    ])
                    ->default('page'),
                Forms\Components\Select::make('parent_id')
                    ->label('Parent Page')
                    ->options(Page::all()->pluck('title', 'id')->toArray())
                    ->nullable(),
                Forms\Components\Toggle::make('is_published')
                    ->label('Published'),
                Forms\Components\DateTimePicker::make('published_at')
                    ->nullable(),
                Forms\Components\Textarea::make('meta_data')
                    ->label('Meta Data')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug')->sortable()->searchable(),
                Tables\Columns\BooleanColumn::make('is_published'),
                Tables\Columns\TextColumn::make('published_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
}