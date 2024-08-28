@php
    $id = $getId();
    $statePath = $getStatePath();
@endphp

<div wire:ignore x-data="{ initLaraberg: function() { 
    document.addEventListener('DOMContentLoaded', function() {
        Laraberg.init('{{ $id }}', {
            height: '600px',
            laravelFilemanager: false,
            sidebar: true,
        });
    });
} }" x-init="initLaraberg">
    <textarea id="{{ $id }}" name="{{ $statePath }}" hidden>{{ $getState() }}</textarea>
</div>

@push('scripts')
    <script src="{{ asset('vendor/laraberg/js/laraberg.js') }}"></script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/laraberg/css/laraberg.css') }}">
@endpush