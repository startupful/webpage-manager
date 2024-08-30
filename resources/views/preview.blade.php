<div class="border p-4 mt-4">
    <h3 class="text-lg font-semibold mb-2">{{ $type === 'header' ? '헤더' : '푸터' }} 프리뷰</h3>
    <div class="preview-content">
        {!! $getContent($this) !!}
    </div>
</div>