<nav x-data="{ open: false }" class="bg-basic w-full top-0 fixed border-b border-color-basic z-50">
    <!-- Primary Navigation Menu -->
    <div class="mx-auto px-4">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('ui.search') }}" :active="request()->routeIs('ui.search')">
                        <span class="text-color-basic">{{ __('Explore') }}</span>
                    </x-nav-link>
                    <x-nav-link href="{{ route('subscribe.show') }}" :active="request()->routeIs('subscribe.show')">
                        <span class="text-color-basic">{{ __('Plan') }}</span>
                    </x-nav-link>
                    <x-nav-link href="{{ route('docs.introduction') }}" :active="request()->routeIs('docs.*')">
                        <span class="text-color-basic">{{ __('Docs') }}</span>
                    </x-nav-link>
                </div>
            </div>

            @if(request()->routeIs('ui.search'))
                <!-- Search Input Form -->
                <div class="hidden sm:flex sm:items-center sm:ms-6 flex-grow">
                    <input type="text" id="search-input" class="w-full py-2 bg-basic text-color-basic border rounded-full" placeholder="Search for UIs..." value="{{ request('q') }}">
                </div>
                @endif

            <div class="hidden sm:flex sm:items-center sm:ms-6">

            <div class="relative mr-3">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                    <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out">
                        @switch(app()->getLocale())
                            @case('ko')
                                한국어
                                @break
                            @case('en')
                                English
                                @break
                            @case('zh')
                                中文
                                @break
                            @case('ja')
                                日本語
                                @break
                            @case('de')
                                Deutsch
                                @break
                            @case('fr')
                                Français
                                @break
                            @case('pt')
                                Português
                                @break
                            @case('hi')
                                हिंदी
                                @break
                            @case('tl')
                                Filipino
                                @break
                            @case('th')
                                ภาษาไทย
                                @break
                            @default
                                {{ strtoupper(app()->getLocale()) }}
                        @endswitch
                        <div class="ml-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link href="{{ route('language.switch', 'ko') }}">
                            한국어
                        </x-dropdown-link>
                        <x-dropdown-link href="{{ route('language.switch', 'en') }}">
                            English
                        </x-dropdown-link>
                        <x-dropdown-link href="{{ route('language.switch', 'zh') }}">
                            中文
                        </x-dropdown-link>
                        <x-dropdown-link href="{{ route('language.switch', 'ja') }}">
                            日本語
                        </x-dropdown-link>
                        <x-dropdown-link href="{{ route('language.switch', 'de') }}">
                            Deutsch
                        </x-dropdown-link>
                        <x-dropdown-link href="{{ route('language.switch', 'fr') }}">
                            Français
                        </x-dropdown-link>
                        <x-dropdown-link href="{{ route('language.switch', 'pt') }}">
                            Português
                        </x-dropdown-link>
                        <x-dropdown-link href="{{ route('language.switch', 'hi') }}">
                            हिंदी
                        </x-dropdown-link>
                        <x-dropdown-link href="{{ route('language.switch', 'tl') }}">
                            Filipino
                        </x-dropdown-link>
                        <x-dropdown-link href="{{ route('language.switch', 'th') }}">
                            ภาษาไทย
                        </x-dropdown-link>
                    </x-slot>

                </x-dropdown>
            </div>

            <div class="mr-3" x-data="{ darkMode: document.documentElement.classList.contains('dark') }">
                <button @click="darkMode = window.toggleDarkMode()" 
                        id="theme-toggle"
                        class="w-6 h-6 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-moon x-show="!darkMode" class="w-5 h-5 text-gray-500" />
                    <x-heroicon-o-sun x-show="darkMode" class="w-5 h-5 text-yellow-500" />
                </button>
            </div>

            @guest
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <a href="{{ route('login') }}" class="text-sm text-color-basic bg-basic hover:bg-secondary-hover rounded-full px-4 py-2 mr-2">
                    {{ __('register.login') }}
                    </a>
                    <a href="{{ route('register') }}" class="text-sm text-color-basic bg-basic hover:bg-secondary-hover rounded-full px-4 py-2">
                    {{ __('register.sign_up') }}
                    </a>
                </div>
            @endguest

                <!-- Teams Dropdown -->
                @auth
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif
                @endauth

                <!-- Settings Dropdown -->
                @auth
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <x-dropdown-link href="{{ route('user.membership') }}">
                                membership
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-color-basic"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
            @endauth

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        @auth
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-color-basic">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-color-sub">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('user.membership') }}" :active="request()->routeIs('user.membership')">
                                membership
                </x-dropdown-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
        @endauth
    </div>
</nav>
