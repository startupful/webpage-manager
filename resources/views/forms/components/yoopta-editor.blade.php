<div
    x-data
    x-init="
        const editor = new YooptaEditor({
            element: $refs.editor,
            content: @entangle($attributes->wire('model'))
        });
    "
    x-ref="editor"
></div>

@push('scripts')
<script src="{{ mix('js/app.js') }}"></script>
@endpush
