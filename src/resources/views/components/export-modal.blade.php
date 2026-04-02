<!-- Export Modal -->
<div x-data="{
        open: false,
        selectedFormat: '',
        currentFilters: {},

        init() {

        }
     }"
     @show-export-modal.window="open = true; currentFilters = $event.detail?.filters || {}"
     @keydown.escape.window="open = false"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-[60] overflow-y-auto"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <!-- Premium Backdrop -->
    <div class="fixed inset-0 bg-zinc-950/40 dark:bg-black/60 backdrop-blur-md transition-opacity"
         @click="open = false"></div>

    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
             class="relative w-full max-w-xl bg-white dark:bg-zinc-900 rounded-[2.5rem] shadow-soft border border-zinc-200 dark:border-zinc-800 overflow-hidden">

            <!-- Modal Header -->
            <div class="px-10 py-8 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-emerald-500/10 dark:bg-emerald-500/20 rounded-2xl flex items-center justify-center border border-emerald-500/20 shadow-inner-soft">
                            <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M7 7h10a2 2 0 012 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-zinc-900 dark:text-white uppercase tracking-tight">Data Extraction</h3>
                            <p class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mt-0.5">Archive telemetry records</p>
                        </div>
                    </div>
                    <button @click="open = false"
                            class="w-10 h-10 rounded-xl flex items-center justify-center text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-10 space-y-8">
                <p class="text-xs font-bold text-zinc-500 dark:text-zinc-400 leading-relaxed uppercase tracking-wide">
                    Select your preferred output architecture. Current filtration parameters will persist through the extraction process.
                </p>

                @php($enabledFormats = config('spatie-activitylog-ui.exports.enabled_formats', []))
                <div class="grid grid-cols-1 gap-4">
                    @if(in_array('xlsx', $enabledFormats))
                    <!-- Excel Option -->
                    <button @click="selectedFormat = 'xlsx'; exportData('xlsx', currentFilters)"
                            class="group relative flex items-center justify-between p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl hover:border-emerald-500/50 dark:hover:border-emerald-500/50 hover:bg-emerald-50/30 dark:hover:bg-emerald-500/5 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center border border-emerald-200/50 dark:border-emerald-500/20 shadow-inner-soft group-hover:scale-110 transition-transform">
                                <svg class="h-7 w-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-black text-zinc-900 dark:text-white uppercase tracking-tight">Structured Document</p>
                                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mt-0.5">MS Excel (.xlsx format)</p>
                            </div>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </button>
                    @endif

                    @if(in_array('csv', $enabledFormats))
                    <!-- CSV Option -->
                    <button @click="selectedFormat = 'csv'; exportData('csv', currentFilters)"
                            class="group relative flex items-center justify-between p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl hover:border-indigo-500/50 dark:hover:border-indigo-500/50 hover:bg-indigo-50/30 dark:hover:bg-indigo-500/5 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center border border-indigo-200/50 dark:border-indigo-500/20 shadow-inner-soft group-hover:scale-110 transition-transform">
                                <svg class="h-7 w-7 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-black text-zinc-900 dark:text-white uppercase tracking-tight">Legacy Flatfile</p>
                                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mt-0.5">Delimited (.csv format)</p>
                            </div>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </button>
                    @endif

                    @if(in_array('pdf', $enabledFormats))
                    <!-- PDF Option -->
                    <button @click="selectedFormat = 'pdf'; exportData('pdf', currentFilters)"
                            class="group relative flex items-center justify-between p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl hover:border-red-500/50 dark:hover:border-red-500/50 hover:bg-red-50/30 dark:hover:bg-red-500/5 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-red-100 dark:bg-red-900/30 rounded-2xl flex items-center justify-center border border-red-200/50 dark:border-red-500/20 shadow-inner-soft group-hover:scale-110 transition-transform">
                                <svg class="h-7 w-7 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-black text-zinc-900 dark:text-white uppercase tracking-tight">Formal Report</p>
                                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mt-0.5">Portable Doc (.pdf format)</p>
                            </div>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </button>
                    @endif

                    @if(in_array('json', $enabledFormats))
                    <!-- JSON Option -->
                    <button @click="selectedFormat = 'json'; exportData('json', currentFilters)"
                            class="group relative flex items-center justify-between p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl hover:border-purple-500/50 dark:hover:border-purple-500/50 hover:bg-purple-50/30 dark:hover:bg-purple-500/5 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center border border-purple-200/50 dark:border-purple-500/20 shadow-inner-soft group-hover:scale-110 transition-transform">
                                <svg class="h-7 w-7 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M7 7h10a2 2 0 012 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z"></path>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-black text-zinc-900 dark:text-white uppercase tracking-tight">Machine Payload</p>
                                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mt-0.5">Serialized (.json format)</p>
                            </div>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center group-hover:bg-purple-500 group-hover:text-white transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </button>
                    @endif
                </div>

                <!-- Active Filters Context -->
                <div x-show="Object.keys(currentFilters).length > 0"
                     class="p-6 bg-zinc-50 dark:bg-zinc-800/30 border border-zinc-100 dark:border-zinc-800 rounded-3xl">
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-zinc-900 flex items-center justify-center border border-zinc-200 dark:border-zinc-800 text-indigo-500">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-[10px] font-black text-zinc-900 dark:text-white uppercase tracking-widest">Active Constraints</h4>
                            <div class="mt-3 flex flex-wrap gap-1.5">
                                <template x-for="[key, value] in Object.entries(currentFilters)" :key="key">
                                    <span x-show="value && value !== '' && !(Array.isArray(value) && value.length === 0)"
                                          class="px-2 py-1 bg-white dark:bg-zinc-800 border border-zinc-100 dark:border-zinc-700 rounded-md text-[9px] font-black text-zinc-500 uppercase tracking-tighter">
                                        <span class="text-zinc-400" x-text="key.replace('_', ' ')"></span>:
                                        <span class="text-zinc-900 dark:text-zinc-300" x-text="Array.isArray(value) ? value.join(', ') : value"></span>
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Warnings/Signals -->
                <div x-show="Object.keys(currentFilters).length === 0 || !Object.values(currentFilters).some(v => v && v !== '' && !(Array.isArray(v) && v.length === 0))"
                     class="p-6 bg-red-50/50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-3xl">
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-950 flex items-center justify-center border border-red-200 dark:border-red-900 text-red-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 13.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-[10px] font-black text-red-900 dark:text-red-400 uppercase tracking-widest">Unconstrained Export</h4>
                            <p class="text-[10px] font-bold text-red-700 dark:text-red-500/70 mt-1 leading-relaxed uppercase tracking-tighter">Warning: Universal data dump initiated. This may result in significant processing latency.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-10 py-8 bg-zinc-50/50 dark:bg-zinc-900/50 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                <span class="text-[9px] font-black text-zinc-400 dark:text-zinc-600 uppercase tracking-[0.3em]">Telemetry Transmission System</span>
                <button @click="open = false"
                        class="px-8 py-3 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-[10px] font-black text-zinc-900 dark:text-white uppercase tracking-widest rounded-2xl shadow-soft hover:bg-zinc-50 dark:hover:bg-zinc-750 transition-all">
                    Dismiss
                </button>
            </div>
        </div>
    </div>
</div>
