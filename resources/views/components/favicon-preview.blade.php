@if(file_exists(public_path('favicon.ico')))
    <div>
        <label class="text-sm font-medium text-gray-700">현재 파비콘</label>
        <div class="mt-1">
            <img src="{{ asset('favicon.ico') }}" alt="Current Favicon" class="max-w-[100px] max-h-[100px]">
        </div>
    </div>
@endif