<div>
    <div class="mb-4">
        <h3 class="text-lg font-medium">메뉴 목록</h3>
        <p class="text-sm text-gray-500">드래그하여 메뉴 순서를 변경할 수 있습니다.</p>
    </div>

    <ul id="menu-items" class="space-y-2">
        @foreach($menuItems as $item)
            <li class="flex items-center space-x-2 p-2 bg-white rounded-md shadow cursor-move" data-id="{{ $item['id'] }}">
                <span class="flex-grow">{{ $item['title'] }}</span>
                <button wire:click="editMenuItem({{ $item['id'] }})" class="text-blue-600 hover:text-blue-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </button>
                <button wire:click="deleteMenuItem({{ $item['id'] }})" class="text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </li>
        @endforeach
    </ul>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('livewire:load', function () {
        var el = document.getElementById('menu-items');
        var sortable = new Sortable(el, {
            animation: 150,
            ghostClass: 'bg-gray-100',
            onEnd: function (evt) {
                var itemEl = evt.item;
                var newIndex = evt.newIndex;
                var oldIndex = evt.oldIndex;

                var orderedIds = Array.from(el.children).map(li => li.dataset.id);
                @this.call('reorderMenuItems', orderedIds);
            },
        });
    });
</script>
@endpush