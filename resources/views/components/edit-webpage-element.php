<x-filament-panels::page>
    <x-filament-forms::form wire:submit="save">
        {{ $this->form }}

        <x-filament-forms::actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-forms::form>
</x-filament-panels::page>