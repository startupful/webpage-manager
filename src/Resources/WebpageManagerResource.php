<?php

namespace Startupful\WebpageManager\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Startupful\StartupfulPlugin\Models\PluginSetting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\Section;
use Filament\Resources\Pages\Page;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class WebpageManagerResource extends Resource
{
    protected static ?string $model = PluginSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationGroup = 'Webpage Manager';

    protected static ?string $navigationLabel = '기본 설정';

    protected static ?string $modelLabel = '기본 설정';

    protected static ?string $pluralModelLabel = '기본 설정';

    protected static ?string $breadcrumb = '기본 설정';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getFormSchema())
            ->statePath('data');
    }

    public static function getFormSchema(): array
    {
        return [
            Forms\Components\View::make('webpage-manager::components.google-preview')
                ->columnSpan('full'),
            Forms\Components\Section::make('')
                ->schema([
                    Forms\Components\Group::make([
                        Forms\Components\FileUpload::make('homepage_favicon')
                            ->label('홈페이지 파비콘')
                            ->image()
                            ->maxSize(1024)
                            ->directory('favicons')
                            ->visibility('public')
                            ->downloadable()
                            ->imagePreviewHeight('100')
                            ->acceptedFileTypes(['image/x-icon', 'image/vnd.microsoft.icon', 'image/ico', 'image/png', 'image/jpeg', 'image/gif'])
                            ->afterStateUpdated(function (Forms\Components\FileUpload $component, $state) {
                                if ($state instanceof TemporaryUploadedFile) {
                                    $faviconPath = public_path('favicon.ico');
                                    
                                    Log::info('Uploading favicon', ['state' => $state, 'path' => $state->getFilename()]);
                                    
                                    // 기존 파일 백업
                                    if (File::exists($faviconPath)) {
                                        File::copy($faviconPath, public_path('favicon.ico.bak'));
                                    }
                                    
                                    try {
                                        // 새 파일 복사
                                        $uploadedFile = $state->store('favicons', 'public');
                                        $storedPath = Storage::disk('public')->path($uploadedFile);
                                        File::copy($storedPath, $faviconPath);
                                        
                                        // 권한 설정
                                        chmod($faviconPath, 0644);
                                        
                                        // 컴포넌트 상태를 배열로 설정
                                        $component->state([$uploadedFile]);
                                        
                                        Log::info('Favicon uploaded successfully', ['path' => $faviconPath]);
                                    } catch (\Exception $e) {
                                        // 오류 발생 시 백업 복원
                                        if (File::exists(public_path('favicon.ico.bak'))) {
                                            File::move(public_path('favicon.ico.bak'), $faviconPath);
                                        }
                                        Log::error('Failed to upload favicon', ['error' => $e->getMessage()]);
                                        throw $e;
                                    }
                                } elseif (is_string($state)) {
                                    // 이미 업로드된 파일의 경우
                                    $component->state([$state]);
                                }
                            }),
                        Forms\Components\View::make('webpage-manager::components.favicon-preview')
                            ->visible(fn ($get) => empty($get('homepage_favicon')) && file_exists(public_path('favicon.ico')))
                    ])->columnSpan(1),
                    Forms\Components\Group::make([
                        Forms\Components\TextInput::make('homepage_title')
                            ->label('홈페이지 제목')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $state) {
                                static::updateEnvFile('APP_NAME', $state);
                            }),
                        Forms\Components\TextInput::make('homepage_url')
                            ->label('홈페이지 주소')
                            ->required()
                            ->url()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $state) {
                                static::updateEnvFile('APP_URL', $state);
                            }),
                        Forms\Components\Textarea::make('homepage_description')
                            ->label('홈페이지 설명')
                            ->maxLength(1000),
                        Forms\Components\Select::make('site_language')
                            ->label('사이트 언어')
                            ->options([
                                'ko' => '한국어',
                                'en' => 'English',
                                'ja' => '日本語',
                                'zh' => '中文',
                            ])
                            ->required(),
                    ])->columnSpan(1),
                ])
                ->columns(2)
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\EditWebpageManager::route('/'),
        ];
    }

    public static function updateEnvFile($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                $key . '=' . env($key),
                $key . '=' . $value,
                file_get_contents($path)
            ));
        }

        Artisan::call('config:clear');
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('plugin_name', 'webpage-manager');
    }
}