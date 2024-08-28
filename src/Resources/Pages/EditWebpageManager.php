<?php

namespace Startupful\WebpageManager\Resources\Pages;

use Startupful\WebpageManager\Resources\WebpageManagerResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Livewire\Attributes\Reactive;
use Startupful\StartupfulPlugin\Models\PluginSetting;
use Filament\Notifications\Notification;

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
        $generalSettings = PluginSetting::firstOrCreate(
            ['plugin_name' => 'webpage-manager', 'plugin_id' => 1, 'key' => 'settings'],
            [
                'value' => json_encode([
                    'homepage_favicon' => null,
                    'homepage_title' => config('app.name'),
                    'homepage_url' => config('app.url'),
                    'homepage_description' => '',
                    'site_language' => config('app.locale'),
                ])
            ]
        );

        $themeSettings = PluginSetting::firstOrCreate(
            ['plugin_name' => 'webpage-manager', 'plugin_id' => 1, 'key' => 'theme'],
            [
                'value' => json_encode([
                    'container_width' => 'container',
                    'primary_color' => '#000000',
                    'secondary_color' => '#ffffff',
                    'app_logo' => null,
                ])
            ]
        );

        $this->record = $generalSettings;

        $data = json_decode($generalSettings->value, true);
        $themeData = json_decode($themeSettings->value, true);

        // Merge general and theme settings
        $data = array_merge($data, $themeData);

        // 파비콘이 존재하는지 확인
        $faviconPath = public_path('favicon.ico');
        if (file_exists($faviconPath)) {
            $data['homepage_favicon'] = 'favicon.ico';
        } else {
            $data['homepage_favicon'] = null;
        }

        // 앱 로고가 존재하는지 확인
        $logoPath = public_path('logo.png');
        if (file_exists($logoPath)) {
            $data['app_logo'] = 'logo.png';
        } else {
            $data['app_logo'] = null;
        }

        $this->form->fill($data);

        Log::info('EditWebpageManager mount: Form filled', ['form_data' => $this->form->getState()]);
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $data = $this->form->getState();

        Log::info('EditWebpageManager save: Form data before save', ['form_data' => $data]);

        // 일반 설정 저장
        $generalSettings = [
            'homepage_favicon',
            'homepage_title',
            'homepage_url',
            'homepage_description',
            'site_language',
        ];
        $generalData = array_intersect_key($data, array_flip($generalSettings));
        $this->record->update(['value' => json_encode($generalData)]);

        // 테마 설정 저장
        $themeSettings = [
            'container_width',
            'primary_color',
            'secondary_color',
            'app_logo',
        ];
        $themeData = array_intersect_key($data, array_flip($themeSettings));

        if (isset($themeData['app_logo']) && is_array($themeData['app_logo'])) {
            $themeData['app_logo'] = $themeData['app_logo'][0] ?? null;
        }

        PluginSetting::updateOrCreate(
            ['plugin_name' => 'webpage-manager', 'plugin_id' => 1, 'key' => 'theme'],
            ['value' => json_encode($themeData)]
        );

        // env 파일 업데이트
        WebpageManagerResource::updateEnvFile('APP_NAME', $data['homepage_title']);
        WebpageManagerResource::updateEnvFile('APP_URL', $data['homepage_url']);

        if ($shouldSendSavedNotification) {
            Notification::make()
                ->title('설정이 저장되었습니다.')
                ->success()
                ->send();
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