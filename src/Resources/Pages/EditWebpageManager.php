<?php

namespace Startupful\WebpageManager\Resources\Pages;

use Startupful\WebpageManager\Resources\WebpageManagerResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Livewire\Attributes\Reactive;

class EditWebpageManager extends EditRecord
{
    protected static string $resource = WebpageManagerResource::class;

    #[Reactive]
    public $formData;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('저장')
                ->action('save')
                ->color('primary'),
        ];
    }

    public function mount($record = null): void
    {
        $record = static::getResource()::getModel()::firstOrCreate(
            ['plugin_name' => 'webpage-manager', 'plugin_id' => 1],
            [
                'key' => 'settings',
                'value' => json_encode([
                    'homepage_favicon' => null,
                    'homepage_title' => config('app.name'),
                    'homepage_url' => config('app.url'),
                    'homepage_description' => '',
                    'site_language' => config('app.locale'),
                ])
            ]
        );

        $this->record = $record;

        Log::info('EditWebpageManager mount: Record loaded', ['record' => $record->toArray()]);

        $data = $record->data ?: [];

        // 파비콘이 존재하는지 확인
        $faviconPath = public_path('favicon.ico');
        Log::info('Favicon path', ['path' => $faviconPath]);
        
        if (file_exists($faviconPath)) {
            Log::info('Favicon file exists');
            $data['homepage_favicon'] = 'favicon.ico';
            Log::info('Favicon path set', ['path' => 'favicon.ico']);
        } else {
            Log::info('Favicon file does not exist');
            $data['homepage_favicon'] = null;
        }

        // env 파일에서 기본 값 불러오기
        $data['homepage_title'] = $data['homepage_title'] ?? Config::get('app.name');
        $data['homepage_url'] = $data['homepage_url'] ?? Config::get('app.url');
        $data['site_language'] = $data['site_language'] ?? Config::get('app.locale');
        $data['homepage_description'] = $data['homepage_description'] ?? '';

        $this->form->fill($data);

        Log::info('EditWebpageManager mount: Form filled', ['form_data' => $this->form->getState()]);
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $data = $this->form->getState();

        Log::info('EditWebpageManager save: Form data before save', ['form_data' => $data]);

        // 파비콘 처리
        if (empty($data['homepage_favicon']) && file_exists(public_path('favicon.ico'))) {
            $data['homepage_favicon'] = 'favicon.ico';
        }

        $this->record->update(['data' => $data]);

        Log::info('EditWebpageManager save: Record updated', ['updated_record' => $this->record->toArray()]);

        // env 파일 업데이트
        WebpageManagerResource::updateEnvFile('APP_NAME', $data['homepage_title']);
        WebpageManagerResource::updateEnvFile('APP_URL', $data['homepage_url']);

        if ($shouldSendSavedNotification) {
            $this->notify('success', '설정이 저장되었습니다.');
        }

        if ($shouldRedirect) {
            $this->redirect($this->getRedirectUrl());
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return '기본 설정';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}