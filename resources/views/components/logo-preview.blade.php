@if(file_exists(public_path('logo.png')))
    <div>
        <label class="text-sm font-medium text-gray-700">현재 로고</label>
        <div class="mt-1">
            <img src="{{ asset('logo.png') }}" alt="Current Favicon" class="max-w-[100px] max-h-[100px]">
        </div>
    </div>
@endif