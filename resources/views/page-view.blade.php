<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('vendor/laraberg/css/laraberg.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <style>
        {!! $customStyles !!}
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
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen">
        @foreach($headers as $header)
            {!! $header->code !!}
        @endforeach

        <main class="pt-20 custom-container-width mx-auto">
            {!! $page->render_content() !!}
        </main>

        <footer class="mt-8 text-gray-600">
            @foreach($footers as $footer)
                {!! $footer->code !!}
            @endforeach
        </footer>
    </div>

    <script src="{{ asset('vendor/laraberg/js/laraberg.js') }}"></script>
    <script>
    var headerMenuData = @json($headerMenuData);
    var footerMenuData = @json($footerMenuData);

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
        renderMenu(footerMenuData, 'footerMenu', true);

        const header = document.getElementById('main-header');
        const scrollThreshold = 50;

        if (header) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > scrollThreshold) {
                    header.classList.add('shadow-[0px_2px_4px_rgba(0,0,0,0.1)]');
                } else {
                    header.classList.remove('shadow-[0px_2px_4px_rgba(0,0,0,0.1)]');
                }
            });
        } else {
            console.error('Header element not found');
        }

        var mobileMenuButton = document.getElementById('mobile-menu-button');
        var closeMobileMenuButton = document.getElementById('close-mobile-menu');
        var mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && mobileMenu && closeMobileMenuButton) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.add('active');
            });

            closeMobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.remove('active');
            });
        }

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
</body>
</html>