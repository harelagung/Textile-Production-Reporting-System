<!-- Navigation -->
<nav
    class="bg-white/80 dark:bg-gray-800/80 glass-effect border-b border-gray-200/20 dark:border-gray-700/20 sticky top-0 z-50 backdrop-blur-xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo & Brand -->
            <div class="flex items-center space-x-4">
                {{-- <div class="flex-shrink-0">
                        <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                    </div> --}}
                <div class="hidden md:block">
                    <h1
                        class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 bg-clip-text text-transparent">
                        PT. Mitrasetia Ekaperwira
                    </h1>
                </div>
            </div>

            <!-- Navigation Menu -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-4">
                    <a href="{{ route('report.index') }}"
                        class="nav-link px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
           {{ request()->routeIs('report.index')
               ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-900/50'
               : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                        Laporan
                    </a>

                    <a href="{{ route('history.index') }}"
                        class="nav-link px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
           {{ request()->routeIs('history.index')
               ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-900/50'
               : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                        Riwayat
                    </a>
                </div>
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center space-x-4">
                <!-- Profile Dropdown -->
                <div class="relative">
                    <!-- Profile Button -->
                    <button id="profile-dropdown-button"
                        class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        <img src={{ asset('images/profile.png') }} alt="Profile"
                            class="w-8 h-8 rounded-full object-cover ring-2 ring-white dark:ring-gray-700">
                        <div class="hidden sm:block text-left">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->nip }}</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="profile-dropdown"
                        class="hidden absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 glass-effect z-50">
                        <div class="p-4 border-b border-gray-200/50 dark:border-gray-700/50">
                            <div class="flex items-center space-x-3">
                                <img src={{ asset('images/profile.png') }} alt="Profile"
                                    class="w-12 h-12 rounded-full object-cover">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ Auth::user()->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ Auth::user()->department->name }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="py-2">
                            <!-- Theme Toggle -->
                            <div class="px-4 py-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tema</span>
                                    <button id="theme-toggle-dropdown"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-200 dark:bg-blue-600 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                        <span id="theme-toggle-circle"
                                            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform dark:translate-x-6 translate-x-1"></span>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <span id="theme-text">Mode Terang</span>
                                </p>
                            </div>

                            <hr class="my-2 border-gray-200/50 dark:border-gray-700/50">

                            <!-- Profile Link -->
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                    </path>
                                </svg>
                                Profile Saya
                            </a>

                            <!-- Settings Link -->
                            {{-- <a href="#"
                                    class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Pengaturan
                                </a>

                                <hr class="my-2 border-gray-200/50 dark:border-gray-700/50"> --}}

                            <!-- Logout Form -->
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                            <button onclick="document.getElementById('logout-form').submit();"
                                class="flex items-center w-full px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200 text-left">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                Keluar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" type="button"
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu"
        class="hidden md:hidden bg-white/95 dark:bg-gray-800/95 glass-effect border-t border-gray-200/20 dark:border-gray-700/20">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="#"
                class="block px-3 py-2 rounded-md text-base font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                Laporan
            </a>
            <a href="#"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700/50">
                Riwayat
            </a>
        </div>
    </div>
</nav>
