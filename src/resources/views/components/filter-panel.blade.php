<!-- Filter Panel Component -->
<div x-data="filterPanel()"
     x-init="init()"
     @clear-filters.window="clearAllFilters()"
     class="overflow-hidden rounded-3xl border border-slate-200 bg-white/92 shadow-soft transition-all duration-300 dark:border-slate-800 dark:bg-slate-900/88">

    <!-- Header -->
    <div class="flex items-center justify-between border-b border-slate-200 bg-stone-50/85 px-5 py-4 dark:border-slate-800 dark:bg-slate-800/35">
        <div class="flex items-center space-x-2.5">
            <div class="rounded-lg bg-teal-50 p-1.5 dark:bg-teal-950/30">
                <svg class="h-4 w-4 text-teal-700 dark:text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                </svg>
            </div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-slate-100">Filters</h3>
            <span x-show="hasActiveFilters" x-cloak
                  class="flex h-2 w-2 animate-pulse rounded-full bg-teal-500"></span>
        </div>

        <div class="flex items-center space-x-1">
            <!-- Saved Views Dropdown -->
            @if(config('spatie-activitylog-ui.features.saved_views', true))
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" 
                        class="rounded-lg p-1.5 text-slate-400 transition-all hover:bg-white hover:text-teal-700 dark:hover:bg-slate-800 dark:hover:text-teal-300"
                        title="Saved Views">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                </button>
                <div x-show="open" x-cloak @click.away="open = false" 
                     class="glass absolute right-0 z-30 mt-2 w-56 overflow-hidden rounded-2xl border shadow-xl">
                    <div class="p-2 space-y-1">
                        <template x-for="view in savedViews" :key="view.id">
                            <div class="flex items-center group">
                                <button @click="loadSavedView(view); open = false" 
                                        class="flex-1 rounded-lg px-3 py-2 text-left text-xs font-medium text-slate-700 transition-colors hover:bg-teal-50 dark:text-slate-300 dark:hover:bg-teal-950/20"
                                        x-text="view.name"></button>
                                <button @click.stop="$dispatch('delete-view', view.id)" 
                                        class="p-2 text-zinc-400 hover:text-rose-500 opacity-0 group-hover:opacity-100 transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </template>
                                <button @click="showSaveViewModal(); open = false" 
                                class="w-full rounded-lg border border-dashed border-teal-200 px-3 py-2 text-left text-xs font-bold text-teal-700 hover:bg-teal-50 dark:border-teal-800/50 dark:text-teal-300 dark:hover:bg-teal-950/20">
                            + Save This View
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <button @click="clearAllFilters()" :disabled="!hasActiveFilters"
                    class="rounded-lg p-1.5 text-slate-400 transition-all hover:bg-white hover:text-rose-500 disabled:opacity-30 dark:hover:bg-slate-800"
                    title="Clear All">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

            <!-- Ultra Compact Button Group (md to lg) - MacBook Pro 13" -->
            <div class="hidden md:flex lg:hidden items-center space-x-0.5">
                <!-- Toggle Filters (Ultra Compact) -->
                <button @click="expanded = !expanded"
                        class="inline-flex items-center justify-center p-1 border border-gray-300 dark:border-gray-600 shadow-sm rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors"
                        :title="expanded ? 'Hide Filters' : 'Show Filters'">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                    </svg>
                </button>

                <!-- Clear Filters (Ultra Compact) -->
                <button @click="clearAllFilters()"
                        :disabled="!hasActiveFilters"
                        class="inline-flex items-center justify-center p-1 border border-gray-300 dark:border-gray-600 shadow-sm rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        title="Clear All Filters">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Saved Views (Ultra Compact) -->
                @if(config('spatie-activitylog-ui.features.saved_views', true))
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="inline-flex items-center justify-center p-1 border border-gray-300 dark:border-gray-600 shadow-sm rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors"
                            title="Saved Views">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                    </button>

                    <div x-show="open"
                         x-cloak
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 dark:ring-white dark:ring-opacity-10 focus:outline-none z-20">
                        <div class="py-1">
                            <template x-for="savedView in savedViews" :key="savedView.id">
                                <div class="flex items-center justify-between hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <button @click="loadSavedView(savedView); open = false"
                                            class="flex-grow text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300"
                                            x-text="savedView.name">
                                    </button>
                                    <button @click.stop="$dispatch('delete-view', savedView.id)"
                                            class="px-2 py-2 text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors"
                                            title="Delete view">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>

                            <div x-show="savedViews.length === 0"
                                 class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                No saved views
                            </div>

                            <div class="border-t border-gray-100 dark:border-gray-700">
                                <button @click="showSaveViewModal(); open = false"
                                        class="block w-full text-left px-4 py-2 text-sm text-blue-600 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    + Save Current View
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Icon-Only Button Group (lg to xl) - Medium Screens -->
            <div class="hidden lg:flex xl:hidden items-center space-x-1">
                <!-- Toggle Filters (Icon Only) -->
                <button @click="expanded = !expanded"
                        class="inline-flex items-center justify-center p-1.5 border border-gray-300 dark:border-gray-600 shadow-sm rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors"
                        :title="expanded ? 'Hide Filters' : 'Show Filters'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                    </svg>
                </button>

                <!-- Clear Filters (Icon Only) -->
                <button @click="clearAllFilters()"
                        :disabled="!hasActiveFilters"
                        class="inline-flex items-center justify-center p-1.5 border border-gray-300 dark:border-gray-600 shadow-sm rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        title="Clear All Filters">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Saved Views (Icon Only) -->
                @if(config('spatie-activitylog-ui.features.saved_views', true))
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="inline-flex items-center justify-center p-1.5 border border-gray-300 dark:border-gray-600 shadow-sm rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors"
                            title="Saved Views">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                    </button>

                    <div x-show="open"
                         x-cloak
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 dark:ring-white dark:ring-opacity-10 focus:outline-none z-20">
                        <div class="py-1">
                            <template x-for="savedView in savedViews" :key="savedView.id">
                                <div class="flex items-center justify-between hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <button @click="loadSavedView(savedView); open = false"
                                            class="flex-grow text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300"
                                            x-text="savedView.name">
                                    </button>
                                    <button @click.stop="$dispatch('delete-view', savedView.id)"
                                            class="px-2 py-2 text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors"
                                            title="Delete view">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>

                            <div x-show="savedViews.length === 0"
                                 class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                No saved views
                            </div>

                            <div class="border-t border-gray-100 dark:border-gray-700">
                                <button @click="showSaveViewModal(); open = false"
                                        class="block w-full text-left px-4 py-2 text-sm text-blue-600 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    + Save Current View
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Compact Button Group (xl and up) - MacBook Pro 13" -->
            <div class="hidden xl:flex items-center space-x-1.5">
                <!-- Saved Views -->
                @if(config('spatie-activitylog-ui.features.saved_views', true))
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="inline-flex items-center px-2 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-xs rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                        <span class="text-xs">Views</span>
                        <svg class="ml-1 -mr-0.5 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open"
                         x-cloak
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-left absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 dark:ring-white dark:ring-opacity-10 focus:outline-none z-20">
                        <div class="py-1">
                            <template x-for="savedView in savedViews" :key="savedView.id">
                                <div class="flex items-center justify-between hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <button @click="loadSavedView(savedView); open = false"
                                            class="flex-grow text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300"
                                            x-text="savedView.name">
                                    </button>
                                    <button @click.stop="$dispatch('delete-view', savedView.id)"
                                            class="px-2 py-2 text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors"
                                            title="Delete view">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>

                            <div x-show="savedViews.length === 0"
                                 class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                No saved views
                            </div>

                            <div class="border-t border-gray-100 dark:border-gray-700">
                                <button @click="showSaveViewModal(); open = false"
                                        class="block w-full text-left px-4 py-2 text-sm text-blue-600 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    + Save Current View
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Toggle Filters -->
                <button @click="expanded = !expanded"
                        class="inline-flex items-center px-2 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-xs rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                    </svg>
                    <span class="text-xs" x-text="expanded ? 'Hide' : 'Show'"></span>
                </button>

                <!-- Clear Filters -->
                <button @click="clearAllFilters()"
                        :disabled="!hasActiveFilters"
                        class="inline-flex items-center px-2 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-xs rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="text-xs">Clear</span>
                </button>
            </div>

            <!-- Small Screen Button Group (sm to md) - Tablet Portrait -->
            <div class="hidden sm:flex md:hidden items-center space-x-1">
                <!-- Toggle Filters (Small Screen) -->
                <button @click="expanded = !expanded"
                        class="inline-flex items-center justify-center p-1.5 border border-gray-300 dark:border-gray-600 shadow-sm rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors"
                        :title="expanded ? 'Hide Filters' : 'Show Filters'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                    </svg>
                </button>

                <!-- Clear Filters (Small Screen) -->
                <button @click="clearAllFilters()"
                        :disabled="!hasActiveFilters"
                        class="inline-flex items-center justify-center p-1.5 border border-gray-300 dark:border-gray-600 shadow-sm rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        title="Clear All Filters">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Saved Views (Small Screen) -->
                @if(config('spatie-activitylog-ui.features.saved_views', true))
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="inline-flex items-center justify-center p-1.5 border border-gray-300 dark:border-gray-600 shadow-sm rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors"
                            title="Saved Views">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                    </button>

                    <div x-show="open"
                         x-cloak
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 dark:ring-white dark:ring-opacity-10 focus:outline-none z-20">
                        <div class="py-1">
                            <template x-for="savedView in savedViews" :key="savedView.id">
                                <div class="flex items-center justify-between hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <button @click="loadSavedView(savedView); open = false"
                                            class="flex-grow text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300"
                                            x-text="savedView.name">
                                    </button>
                                    <button @click.stop="$dispatch('delete-view', savedView.id)"
                                            class="px-2 py-2 text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors"
                                            title="Delete view">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>

                            <div x-show="savedViews.length === 0"
                                 class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                No saved views
                            </div>

                            <div class="border-t border-gray-100 dark:border-gray-700">
                                <button @click="showSaveViewModal(); open = false"
                                        class="block w-full text-left px-4 py-2 text-sm text-blue-600 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    + Save Current View
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Mobile Button Group -->
            <div class="flex sm:hidden items-center space-x-1">
                <!-- Toggle Filters (Mobile) -->
                <button @click="expanded = !expanded"
                        class="inline-flex items-center justify-center p-2 border border-gray-300 dark:border-gray-600 shadow-sm rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                        :title="expanded ? 'Hide Filters' : 'Show Filters'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                    </svg>
                </button>

                <!-- Clear Filters (Mobile) -->
                <button @click="clearAllFilters()"
                        :disabled="!hasActiveFilters"
                        class="inline-flex items-center justify-center p-2 border border-gray-300 dark:border-gray-600 shadow-sm rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        title="Clear Filters">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Mobile Actions Menu -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="inline-flex items-center justify-center p-2 border border-gray-300 dark:border-gray-600 shadow-sm rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                            title="More Actions">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>

                    <div x-show="open"
                         x-cloak
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 dark:ring-white dark:ring-opacity-10 focus:outline-none z-20">
                        <div class="py-1">
                            <!-- Saved Views Section -->
                            @if(config('spatie-activitylog-ui.features.saved_views', true))
                            <div class="px-4 py-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Saved Views
                            </div>

                            <template x-for="savedView in savedViews" :key="savedView.id">
                                <div class="flex items-center justify-between hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <button @click="loadSavedView(savedView); open = false"
                                            class="flex-grow text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300"
                                            x-text="savedView.name">
                                    </button>
                                    <button @click.stop="$dispatch('delete-view', savedView.id)"
                                            class="px-2 py-2 text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors"
                                            title="Delete view">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>

                            <div x-show="savedViews.length === 0"
                                 class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                No saved views
                            </div>

                            <div class="border-t border-gray-100 dark:border-gray-700">
                                <button @click="showSaveViewModal(); open = false"
                                        class="block w-full text-left px-4 py-2 text-sm text-blue-600 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Save Current View
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Content -->
    <div x-show="expanded"
         x-collapse
         class="px-4 sm:px-6 py-4 space-y-4 sm:space-y-6">

        <!-- Search -->
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Search
            </label>
            <div class="relative">
                <input type="text"
    <div class="p-5 space-y-6">
        <!-- Search Section -->
        <div class="space-y-2">
            <label for="search" class="pl-1 text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Global Search</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400 transition-colors group-focus-within:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="search" x-model="filters.search" @input.debounce.500ms="applyFilters()"
                       placeholder="Search by ID, properties..."
                       class="block w-full rounded-2xl border border-slate-200 bg-stone-50 py-2.5 pl-10 pr-3 text-sm shadow-inner-soft transition-all placeholder:text-slate-400 focus:border-teal-400 focus:ring-2 focus:ring-teal-500/15 dark:border-slate-700/50 dark:bg-slate-800/50 dark:text-slate-100 dark:placeholder:text-slate-600">
            </div>
        </div>

        <!-- Date Range Section -->
        <div class="space-y-3">
            <label class="pl-1 text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Time Horizon</label>
            <div class="grid grid-cols-2 gap-2">
                <template x-for="preset in datePresets" :key="preset.value">
                    <button @click="setDatePreset(preset.value)"
                            :class="filters.date_preset === preset.value 
                                ? 'bg-teal-600 text-white shadow-lg shadow-teal-500/20 border-teal-500' 
                                : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:border-teal-300 dark:hover:border-slate-600'"
                            class="px-3 py-2 text-xs font-bold rounded-xl border transition-all duration-200 text-center"
                            x-text="preset.label"></button>
                </template>
            </div>

            <!-- Custom Date Range -->
            <div x-show="filters.date_preset === 'custom'" x-collapse class="pt-2 space-y-3">
                <div class="grid grid-cols-1 gap-3">
                    <div class="space-y-1">
                        <span class="ml-1 text-[10px] font-bold uppercase text-slate-400">From</span>
                        <input type="date" x-model="filters.start_date" @change="applyFilters()"
                               class="block w-full rounded-xl border border-slate-200 bg-stone-50 px-3 py-2 text-xs focus:ring-2 focus:ring-teal-500/15 dark:border-slate-700/50 dark:bg-slate-800/50 dark:text-slate-100">
                    </div>
                    <div class="space-y-1">
                        <span class="ml-1 text-[10px] font-bold uppercase text-slate-400">To</span>
                        <input type="date" x-model="filters.end_date" @change="applyFilters()"
                               class="block w-full rounded-xl border border-slate-200 bg-stone-50 px-3 py-2 text-xs focus:ring-2 focus:ring-teal-500/15 dark:border-slate-700/50 dark:bg-slate-800/50 dark:text-slate-100">
                    </div>
                </div>
            </div>
        </div></div>
        </div>

        <!-- Filter Grid -->
        <div class="grid grid-cols-1 gap-4 sm:gap-6">

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Date Range
                </label>

                    <!-- Date Presets -->
                <div class="flex flex-wrap gap-1 sm:gap-2 mb-3">
                        <template x-for="preset in datePresets" :key="preset.value">
                            <button @click="setDatePreset(preset.value)"
                                :class="{
                                    'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 border-blue-300 dark:border-blue-700': filters.date_preset === preset.value,
                                    'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600': filters.date_preset !== preset.value
                                }"
                                class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-medium border transition-colors whitespace-nowrap"
                                x-text="preset.label">
                        </button>
                        </template>
                    </div>

                    <!-- Custom Date Range -->
                <div x-show="filters.date_preset === 'custom'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="grid grid-cols-1 sm:grid-cols-2 gap-2"
                     x-cloak>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">From Date</label>
                        <input type="date"
                               x-model="filters.start_date"
                               @change="applyFilters()"
                               class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">To Date</label>
                        <input type="date"
                               x-model="filters.end_date"
                               @change="applyFilters()"
                               class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>
            </div>

            <!-- Row for Event Types and Causer (side by side on larger screens) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            <!-- Event Types -->
            <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Event Types
                    </label>
                                                            <div class="space-y-2 max-h-32 sm:max-h-40 overflow-y-auto custom-scrollbar">
                    <template x-for="eventType in availableEventTypes" :key="eventType.value">
                            <label class="flex items-center">
                                <input type="checkbox"
                                       :value="eventType.value"
                                       x-model="filters.event_types"
                                       @change="applyFilters()"
                                       class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300" x-text="eventType.label"></span>
                                <span :class="eventType.color" class="ml-2 inline-block w-2 h-2 rounded-full"></span>
                        </label>
                    </template>
                </div>
            </div>

            <!-- Causer Filter -->
            <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        User/Causer
                    </label>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="relative w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm pl-3 pr-10 py-2 text-left text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <span class="block truncate" x-text="selectedCauserText || 'All users'"></span>
                        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </span>
                    </button>

                    <div x-show="open"
                         x-cloak
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
        </div>

        <!-- Advanced Filters Toggle -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
            <button @click="showAdvanced = !showAdvanced"
                    class="inline-flex items-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors">
                <svg :class="showAdvanced ? 'rotate-90' : ''"
                     class="w-4 h-4 mr-1 transform transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span x-text="showAdvanced ? 'Hide Advanced Filters' : 'Show Advanced Filters'"></span>
            </button>
        </div>

        <!-- Advanced Filters -->
        <div x-show="showAdvanced"
             x-collapse
             class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

            <!-- Subject Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Subject Type
                </label>
                <select x-model="filters.subject_type"
                        @change="applyFilters()"
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm min-w-[200px]">
                    <option value="">All Types</option>
                    <template x-for="subjectType in availableSubjectTypes" :key="subjectType.value">
                        <option :value="subjectType.value" x-text="subjectType.label"></option>
                    </template>
                </select>
            </div>

        </div>
    </div>
</div>
