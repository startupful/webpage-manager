@php
    Log::info('Rendering content in Blade:', ['content' => $content]);
@endphp

@if(is_string($content) && !empty($content))
    <div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">선택된 헤더 미리보기</h3>
        <div class="mt-2 p-4 bg-gray-100 dark:bg-gray-800 rounded-md">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">HTML 코드:</h4>
            <pre class="mt-1 text-sm text-gray-600 dark:text-gray-400 overflow-x-auto">{{ htmlspecialchars($content) }}</pre>
        </div>
        <div class="mt-4">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">렌더링된 헤더:</h4>
            <div class="mt-1 border rounded-md overflow-hidden">
                {!! $content !!}
            </div>
        </div>
    </div>
@else
    <div class="text-gray-500 dark:text-gray-400">헤더를 선택하면 미리보기가 여기에 표시됩니다.</div>
@endif
