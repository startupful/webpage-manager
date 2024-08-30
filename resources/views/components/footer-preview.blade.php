@php
    use Illuminate\Support\Facades\Log;
    use Startupful\WebpageManager\Resources\WebpageElementResource;
    
    $record = $this->getRecord();
    
    $menuData = WebpageElementResource::getMenuData();
    
    // 여기서 footer 코드를 가져옵니다.
    $footerElement = \Startupful\WebpageManager\Models\WebpageElement::where('type', 'footer')->first();
    $footerCode = $footerElement ? $footerElement->code : '';
    
    try {
        $renderedContent = Illuminate\Support\Facades\Blade::render($footerCode);
    } catch (\Exception $e) {
        $renderedContent = '<div class="text-red-500">Error rendering template: ' . $e->getMessage() . '</div>';
    }
@endphp
{!! WebpageElementResource::getTailwindCdn() !!}
<div class="rounded">
    <div class="preview-container">
        {!! $renderedContent !!}
    </div>
</div>

<script>
    var footerMenuData = @json($menuData['footerMenuData']);

    function renderMenu(menuData, targetId, isFooter, isMobile = false) {
        var targetElement = document.getElementById(targetId);
        if (!targetElement) {
            console.error('Target element not found:', targetId);
            return;
        }

        var menuHtml = '';
        menuData.forEach(function(item, index) {
            menuHtml += renderMenuItem(item, isFooter, isMobile, index);
        });
        targetElement.innerHTML = menuHtml;
    }

    function renderMenuItem(item, isFooter, isMobile, index) {
        var html = '<li class="' + (isFooter ? 'mb-6' : (isMobile ? 'block' : 'relative submenu-wrapper')) + '" data-index="' + index + '">';
        html += '<a href="' + item.url + '" target="' + item.target + '" class="' + 
                (isFooter ? 'text-sm font-semibold text-gray-900 hover:text-gray-700' : 
                (isMobile ? 'block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100' :
                'text-gray-700 hover:bg-gray-100 hover:text-gray-900 px-4 py-3 rounded-md text-sm font-medium')) + 
                '">' + item.label + '</a>';
        if (item.children && item.children.length > 0) {
            if (isFooter || isMobile) {
                html += '<ul class="mt-2 space-y-2 ' + (isMobile ? 'ml-4' : '') + '">';
                item.children.forEach(function(child) {
                    html += '<li><a href="' + child.url + '" target="' + child.target + '" class="' + 
                    (isMobile ? 'block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-gray-900 hover:bg-gray-100' :
                    'text-gray-600 hover:text-gray-900') + '">' + child.label + '</a></li>';
                });
                html += '</ul>';
            } else {
                html += '<ul class="submenu w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">';
                item.children.forEach(function(child) {
                    html += '<li><a href="' + child.url + '" target="' + child.target + '" class="block rounded-md m-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">' + child.label + '</a></li>';
                });
                html += '</ul>';
            }
        }
        html += '</li>';
        return html;
    }
</script>