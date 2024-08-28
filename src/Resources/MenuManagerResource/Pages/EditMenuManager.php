<?php

namespace Startupful\WebpageManager\Resources\MenuManagerResource\Pages;

use Startupful\WebpageManager\Resources\MenuManagerResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;

class EditMenuManager extends EditRecord
{
    protected static string $resource = MenuManagerResource::class;

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
            ['plugin_name' => 'webpage-manager', 'plugin_id' => 1, 'key' => 'menu'],
            [
                'value' => json_encode([
                    'unify_menus' => false,
                    'header_menu' => [],
                    'footer_menu' => [],
                    'unified_menu' => [],
                ])
            ]
        );

        $this->record = $record;

        Log::info('EditMenuManager mount: Record loaded', ['record' => $record->toArray()]);

        $data = json_decode($record->value, true) ?: [];

        // 기본값 설정
        $data = array_merge([
            'unify_menus' => false,
            'header_menu' => [],
            'footer_menu' => [],
            'unified_menu' => [],
        ], $data);

        $this->form->fill($data);

        Log::info('EditMenuManager mount: Form filled', ['form_data' => $this->form->getState()]);
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $data = $this->form->getState();

        Log::info('EditMenuManager save: Form data before save', ['form_data' => $data]);

        $this->record->update(['value' => json_encode($data)]);

        Log::info('EditMenuManager save: Record updated', ['updated_record' => $this->record->toArray()]);

        if ($shouldSendSavedNotification) {
            Notification::make()
                ->title('메뉴 설정이 저장되었습니다.')
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
        return '메뉴 설정';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        Log::info('EditMenuManager mutateFormDataBeforeFill: Data before mutation', ['data' => $data]);
        
        $result = parent::mutateFormDataBeforeFill($data);
        
        Log::info('EditMenuManager mutateFormDataBeforeFill: Data after mutation', ['result' => $result]);
        
        return $result;
    }
}