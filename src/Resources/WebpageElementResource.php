<?php

namespace Startupful\WebpageManager\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Startupful\WebpageManager\Models\WebpageElement;
use Startupful\WebpageManager\Resources\WebpageElementResource\Pages;
use Illuminate\Support\Facades\File;
use Filament\Forms\Components\View;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Log;

class WebpageElementResource extends Resource
{
    protected static ?string $model = WebpageElement::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationGroup = 'Webpage Manager';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = '헤더/푸터 설정';

    public static function form(Form $form): Form
    {
        $elementOptions = self::getElementOptionsFromJson();
        $savedElements = WebpageElement::pluck('name', 'name')->toArray();

        $jsonElementNames = collect($elementOptions)->pluck('name', 'name')->toArray();

        $allOptions = array_merge($savedElements, $jsonElementNames);

        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('요소 유형')
                    ->options([
                        'header' => '헤더',
                        'footer' => '푸터',
                    ])
                    ->required()
                    ->reactive(),
                Forms\Components\Select::make('name')
                    ->label('요소 선택')
                    ->options(function (Get $get) use ($allOptions) {
                        $type = $get('type');
                        return array_filter($allOptions, function($key) use ($type) {
                            return strpos($key, $type) === 0;
                        }, ARRAY_FILTER_USE_KEY);
                    })
                    ->reactive()
                    ->afterStateUpdated(function (Set $set, $state) use ($elementOptions) {
                        if ($state) {
                            $element = WebpageElement::where('name', $state)->first();
                            if ($element) {
                                Log::info('Setting content from DB:', ['content' => $element->content]);
                                $set('content', $element->content);
                            } else {
                                $selectedElement = collect($elementOptions)->first(fn ($item) => $item['name'] === $state);
                                if ($selectedElement) {
                                    Log::info('Setting content from JSON:', ['content' => $selectedElement['content']]);
                                    $set('content', $selectedElement['content']);
                                }
                            }
                        } else {
                            Log::warning('No state selected in afterStateUpdated');
                        }
                    })
                    ->required(),
                View::make('webpage-manager::components.element-preview')
                    ->visible(function (Get $get) {
                        $content = $get('content');
                        return !empty($content);
                    })
                    ->viewData([
                        'content' => $form->getState()['content'] ?? ''
                    ])
                    ->columnSpanFull(),
                Forms\Components\Hidden::make('content'),
                Forms\Components\Hidden::make('is_active')
                    ->default(true),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\EditWebpageElement::route('/'),
        ];
    }

    public static function getNavigationUrl(): string
    {
        return static::getUrl('index');
    }

    private static function getElementOptionsFromJson(): array
    {
        $headerPath = __DIR__ . '/../../resources/views/headers.json';
        $footerPath = __DIR__ . '/../../resources/views/footers.json';
        
        if (!File::exists($headerPath) || !File::exists($footerPath)) {
            throw new \Exception("Headers or Footers JSON file not found");
        }

        $headerJson = File::get($headerPath);
        $footerJson = File::get($footerPath);
        
        $headers = json_decode($headerJson, true);
        $footers = json_decode($footerJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Error decoding JSON: " . json_last_error_msg());
        }

        return array_merge($headers, $footers);
    }

    public function getContent(): string
    {
        return $this->form->getState()['content'] ?? '';
    }
}