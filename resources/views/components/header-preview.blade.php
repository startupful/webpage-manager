@php
    use Illuminate\Support\Facades\Log;
    use Startupful\WebpageManager\Resources\WebpageElementResource;
    
    $record = $this->getRecord();
    
    $headerCode = $record->code ?? '';

    $headerCode = preg_replace('/\bfixed\b/', '', $headerCode);
    
    $menuData = WebpageElementResource::getMenuData();
    
    try {
        $renderedContent = Illuminate\Support\Facades\Blade::render($headerCode);
    } catch (\Exception $e) {
        $renderedContent = '<div class="text-red-500">Error rendering template: ' . $e->getMessage() . '</div>';
    }
@endphp

{!! WebpageElementResource::getTailwindCdn() !!}
<style>
        .mobile-menu {
            transition: transform 0.3s ease-in-out;
            transform: translateX(-100%);
        }
        .mobile-menu.active {
            transform: translateX(0);
        }
        .submenu-wrapper {
            position: relative;
        }
        .submenu-wrapper::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            height: 20px;
        }
        .submenu {
            display: none;
            position: absolute;
            left: 0;
            top: calc(100% + 20px);
            z-index: 10;
        }
        .submenu.active {
            display: block;
        }
    </style>
<div class="h-20 rounded">
    <div class="preview-container">
        {!! $renderedContent !!}
    </div>
</div>

<script>
    var headerMenuData = @json($menuData['headerMenuData']);

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

    document.addEventListener('DOMContentLoaded', function() {
        renderMenu(headerMenuData, 'headerMenu', false);
        renderMenu(headerMenuData, 'mobileMenu', false, true);

        // Submenu behavior
        var headerMenu = document.getElementById('headerMenu');
        if (headerMenu) {
            headerMenu.addEventListener('mouseover', function(e) {
                var target = e.target.closest('.submenu-wrapper');
                if (target) {
                    var submenu = target.querySelector('.submenu');
                    if (submenu) {
                        submenu.classList.add('active');
                    }
                }
            });

            headerMenu.addEventListener('mouseout', function(e) {
                var target = e.target.closest('.submenu-wrapper');
                if (target && !target.contains(e.relatedTarget)) {
                    var submenu = target.querySelector('.submenu');
                    if (submenu) {
                        submenu.classList.remove('active');
                    }
                }
            });
        }
    });
</script>