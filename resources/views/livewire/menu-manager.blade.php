<div>
    <div class="mb-4">
        <h3 class="text-lg font-medium">메뉴 추가</h3>
        <form wire:submit.prevent="addMenuItem">
            <div class="grid grid-cols-3 gap-4">
                <input wire:model.defer="newMenuItem.label" type="text" placeholder="메뉴 이름" class="w-full px-3 py-2 border rounded">
                <input wire:model.defer="newMenuItem.url" type="text" placeholder="URL" class="w-full px-3 py-2 border rounded">
                <select wire:model.defer="newMenuItem.target" class="w-full px-3 py-2 border rounded">
                    <option value="_self">현재 창</option>
                    <option value="_blank">새 창</option>
                </select>
            </div>
            <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded">추가</button>
        </form>
    </div>

    <div class="mb-4">
        <h3 class="text-lg font-medium">메뉴 목록 ({{ count($menuItems) }} items)</h3>
        @if(count($menuItems) > 0)
            <ul wire:sortable="reorderMenuItems">
                @foreach($menuItems as $index => $item)
                    <li wire:key="menu-item-{{ $index }}" wire:sortable.item="{{ $index }}" class="flex items-center space-x-2 p-2 bg-white rounded-md shadow my-2">
                        <span wire:sortable.handle class="cursor-move">≡</span>
                        <input wire:model.defer="menuItems.{{ $index }}.label" type="text" class="flex-grow px-2 py-1 border rounded">
                        <input wire:model.defer="menuItems.{{ $index }}.url" type="text" class="flex-grow px-2 py-1 border rounded">
                        <select wire:model.defer="menuItems.{{ $index }}.target" class="px-2 py-1 border rounded">
                            <option value="_self">현재 창</option>
                            <option value="_blank">새 창</option>
                        </select>
                        <button wire:click="updateMenuItem({{ $index }})" class="px-2 py-1 bg-green-500 text-white rounded">수정</button>
                        <button wire:click="deleteMenuItem({{ $index }})" class="px-2 py-1 bg-red-500 text-white rounded">삭제</button>
                    </li>
                @endforeach
            </ul>
        @else
            <p>메뉴 항목이 없습니다.</p>
        @endif
    </div>

    <!-- Debug Information -->
    @if(config('app.debug'))
        <div class="mt-8 p-4 bg-gray-100 rounded">
            <h4 class="font-bold">Debug Info:</h4>
            <pre>{{ json_encode($menuItems, JSON_PRETTY_PRINT) }}</pre>
        </div>
    @endif
</div>