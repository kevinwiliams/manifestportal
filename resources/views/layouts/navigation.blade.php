<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- LEFT SIDE NAV -->
            <div class="flex">

                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-700">
                        Manifest Portal
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">

                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>

                    <x-nav-link :href="route('uploads.index')" :active="request()->routeIs('uploads.*')">
                        Uploads
                    </x-nav-link>

                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                        Reports
                    </x-nav-link>

                    <!-- Admin Section -->
                    <x-nav-link :href="route('admin.truck-mappings.index')" 
                        :active="request()->routeIs('admin.truck-mappings.*')">
                        Admin
                    </x-nav-link>

                </div>
            </div>

            <!-- RIGHT SIDE PROFILE DROPDOWN -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">

                <x-dropdown align="right" width="48">

                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                            <div>{{ Auth::user()->name }}</div>
                            <svg class="ml-2 h-4 w-4" viewBox="0 0 20 20"><path fill="currentColor" d="M5.5 7l4.5 4.5L14.5 7"/></svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>

                </x-dropdown>

            </div>

            <!-- Mobile Menu Button -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100">
                    <svg class="h-6 w-6" viewBox="0 0 20 20">
                        <path x-show="!open" fill="currentColor"
                              d="M3 6h14M3 10h14M3 14h14"/>
                        <path x-show="open" fill="currentColor"
                              d="M6 6l8 8M14 6L6 14"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" class="sm:hidden">

        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('uploads.index')" :active="request()->routeIs('uploads.*')">
                Uploads
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                Reports
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.truck-mappings.index')" 
                :active="request()->routeIs('admin.truck-mappings.*')">
                Admin
            </x-responsive-nav-link>
        </div>

        <!-- Mobile Profile -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Profile
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}"
                         onclick="event.preventDefault(); this.closest('form').submit();">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>

    </div>
</nav>
