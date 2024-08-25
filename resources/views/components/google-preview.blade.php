<h3 class="text-lg font-semibold mb-2">구글 검색 미리보기</h3>
<div class="mb-4 p-4 bg-white dark:bg-gray-900 rounded-lg shadow">
    <div class="flex flex-col items-start">
        <div class="flex flex-row gap-4 items-center justify-center">
            <img src="{{ asset('favicon.ico') }}" alt="Favicon" class="w-7 h-7 object-contain">
            <div class="flex flex-col font-base mr-4">
                <div class="text-sm text-blue-600 dark:text-blue-400 hover:underline">{{ $getState()['homepage_title'] ?? config('app.name') }}</div>
                <div class="text-sm text-green-700 dark:text-green-500">{{ $getState()['homepage_url'] ?? config('app.url') }}</div>
            </div>
        </div>
        <div class="text-xl text-[#99c3ff] dark:text-[#99c3ff] hover:underline">{{ $getState()['homepage_title'] ?? config('app.name') }}</div>
        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2 max-w-[600px]">
            @if(empty($getState()['homepage_description']))
                홈페이지 설명을 입력해 주세요. 해당 입력폼을 입력하지 않을 시 해당 홈페이지의 콘텐츠가 자동으로 색인되어 적용됩니다.
            @else
                {{ $getState()['homepage_description'] }}
            @endif
        </div>
    </div>
</div>