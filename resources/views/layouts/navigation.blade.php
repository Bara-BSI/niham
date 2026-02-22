<nav x-data="{ open: false }" class="glass-panel">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        @if(isset($activeProperty) && $activeProperty->logo_path)
                            <img src="{{ asset('storage/' . $activeProperty->logo_path) }}" alt="{{ $activeProperty->name }} Logo" class="block h-10 w-auto object-contain">
                        @else
                            <x-application-logo class="block h-9 w-auto fill-current text-accent" />
                        @endif
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('assets.index')" :active="request()->routeIs('assets.*')">
                        {{ __('Assets') }}
                    </x-nav-link>
                </div>
                {{-- Admin / Super Admin menus --}}
                @if (Auth::user()->isRole('admin') || Auth::user()->isSuperAdmin())
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                            {{ __('Users') }}
                        </x-nav-link>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                            {{ __('Categories') }}
                        </x-nav-link>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('departments.index')" :active="request()->routeIs('departments.*')">
                            {{ __('Departments') }}
                        </x-nav-link>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.*')">
                            {{ __('Roles') }}
                        </x-nav-link>
                    </div>
                @endif
                {{-- Super Admin only: Properties --}}
                @if (Auth::user()->isSuperAdmin())
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('properties.index')" :active="request()->routeIs('properties.*')">
                            {{ __('Properties') }}
                        </x-nav-link>
                    </div>
                @endif
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 sm:gap-4">
                {{-- Property indicator --}}
                @php
                    $currentProperty = null;
                    if (Auth::user()->isSuperAdmin()) {
                        $activeId = session('active_property_id');
                        $currentProperty = $activeId ? \App\Models\Property::find($activeId) : null;
                    } else {
                        $currentProperty = Auth::user()->property;
                    }
                @endphp

                @if (Auth::user()->isSuperAdmin())
                    {{-- Property Switcher for Super Admin --}}
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-accent text-sm leading-4 font-medium rounded-md text-accent bg-accent/10 hover:bg-accent/20 focus:outline-none transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                {{ $currentProperty ? $currentProperty->name : __('All Properties') }}
                                <svg class="fill-current h-4 w-4 ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            {{-- All Properties option --}}
                            <form method="POST" action="{{ route('properties.switch') }}">
                                @csrf
                                <input type="hidden" name="property_id" value="">
                                <button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-500/10 focus:outline-none focus:bg-gray-500/10 {{ !$currentProperty ? 'font-bold bg-accent/10' : '' }}">
                                    {{ __('All Properties') }}
                                </button>
                            </form>

                            @foreach (\App\Models\Property::orderBy('name')->get() as $prop)
                                <form method="POST" action="{{ route('properties.switch') }}">
                                    @csrf
                                    <input type="hidden" name="property_id" value="{{ $prop->id }}">
                                    <button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-500/10 focus:outline-none focus:bg-gray-500/10 {{ $currentProperty && $currentProperty->id === $prop->id ? 'font-bold bg-accent/10' : '' }}">
                                        {{ $prop->name }}
                                    </button>
                                </form>
                            @endforeach
                        </x-slot>
                    </x-dropdown>
                @elseif ($currentProperty)
                    {{-- Property label for normal users --}}
                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        {{ $currentProperty->name }}
                    </span>
                @endif

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-500/10 focus:outline-none focus:bg-gray-500/10 focus:text-gray-500 transition duration-150 ease-in-out">
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
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('assets.index')" :active="request()->routeIs('assets.index')">
                {{ __('Assets') }}
            </x-responsive-nav-link>
        </div>

        @if (Auth::user()->isRole('admin') || Auth::user()->isSuperAdmin())
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                    {{ __('Users') }}
                </x-responsive-nav-link>
            </div>

            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.index')">
                    {{ __('Categories') }}
                </x-responsive-nav-link>
            </div>

            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('departments.index')" :active="request()->routeIs('departments.index')">
                    {{ __('Departments') }}
                </x-responsive-nav-link>
            </div>

            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')">
                    {{ __('Roles') }}
                </x-responsive-nav-link>
            </div>
        @endif

        @if (Auth::user()->isSuperAdmin())
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('properties.index')" :active="request()->routeIs('properties.*')">
                    {{ __('Properties') }}
                </x-responsive-nav-link>
            </div>

            {{-- Mobile Property Switcher (collapsible) --}}
            <div class="pt-2 pb-3 border-t border-gray-200" x-data="{ switcherOpen: false }">
                <button
                    @click="switcherOpen = !switcherOpen"
                    type="button"
                    class="w-full flex items-center justify-between px-4 py-2 text-sm font-semibold text-gray-700"
                >
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span>{{ $currentProperty ? $currentProperty->name : 'All Properties' }}</span>
                    </span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="switcherOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="switcherOpen" x-collapse x-cloak class="space-y-1 mt-1">
                    <form method="POST" action="{{ route('properties.switch') }}">
                        @csrf
                        <input type="hidden" name="property_id" value="">
                        <button type="submit" class="block w-full text-start px-8 py-2 text-sm {{ !$currentProperty ? 'font-bold text-accent' : 'text-gray-600' }} hover:bg-gray-500/10 transition">
                            All Properties
                        </button>
                    </form>
                    @foreach (\App\Models\Property::orderBy('name')->get() as $prop)
                        <form method="POST" action="{{ route('properties.switch') }}">
                            @csrf
                            <input type="hidden" name="property_id" value="{{ $prop->id }}">
                            <button type="submit" class="block w-full text-start px-8 py-2 text-sm {{ $currentProperty && $currentProperty->id === $prop->id ? 'font-bold text-accent' : 'text-gray-600' }} hover:bg-gray-500/10 transition">
                                {{ $prop->name }}
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                @if ($currentProperty)
                    <div class="font-medium text-xs text-accent mt-1">{{ $currentProperty->name }}</div>
                @endif
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
