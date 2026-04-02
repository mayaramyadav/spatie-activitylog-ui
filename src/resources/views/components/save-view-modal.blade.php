<!-- Save View Modal -->
<div x-data="{ open: false, viewName: '', filters: {} }"
     x-on:show-save-view-modal.window="filters = $event.detail; open = true"
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
             class="relative w-full max-w-lg bg-white dark:bg-zinc-900 rounded-[2.5rem] shadow-soft border border-zinc-200 dark:border-zinc-800 overflow-hidden">

            <!-- Modal Header -->
            <div class="px-10 py-8 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-indigo-500/10 dark:bg-indigo-500/20 rounded-2xl flex items-center justify-center border border-indigo-500/20 shadow-inner-soft">
                            <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-zinc-900 dark:text-white uppercase tracking-tight">Lens Presets</h3>
                            <p class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mt-0.5">Persist filtration configuration</p>
                        </div>
                    </div>
                    <button @click="open = false"
                            class="w-10 h-10 rounded-xl flex items-center justify-center text-zinc-400 hover:text-zinc-900 dark:hover:white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <form @submit.prevent="if(viewName.trim()) { saveView(viewName, filters); viewName = ''; open = false }" class="p-10 space-y-8">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label for="view-name" class="text-[10px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.2em] ml-1">
                            Configuration Alias
                        </label>
                        <input type="text"
                               id="view-name"
                               x-model="viewName"
                               placeholder="E.G. HIGH_SEVERITY_DAMPING"
                               class="block w-full px-5 py-4 bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-2xl text-[11px] font-black uppercase tracking-widest text-zinc-900 dark:text-white placeholder-zinc-300 dark:placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/50 transition-all shadow-inner-soft">
                    </div>

                    <!-- Filter Preview Section -->
                    <div class="p-6 bg-zinc-900 rounded-[2rem] border border-zinc-800 relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500/5 rounded-full blur-2xl"></div>
                        <h4 class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-4 flex items-center">
                            <div class="w-1.5 h-1.5 bg-indigo-500 rounded-full mr-2"></div> Active Parameters
                        </h4>
                        
                        <div class="space-y-2">
                            <template x-if="Object.keys(filters).length === 0">
                                <span class="text-[10px] font-bold text-zinc-600 uppercase tracking-tighter italic">Universal spectrum - No constraints applied</span>
                            </template>
                            <template x-if="Object.keys(filters).length > 0">
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="[key, value] in Object.entries(filters)" :key="key">
                                        <div x-show="value && value !== '' && (!Array.isArray(value) || value.length > 0)"
                                             class="px-2.5 py-1.5 bg-zinc-800 border border-zinc-700 rounded-lg text-[9px] font-black text-zinc-400 uppercase tracking-tight">
                                            <span class="text-zinc-600" x-text="key.replace('_', ' ')"></span>:
                                            <span class="text-zinc-200" x-text="Array.isArray(value) ? value.join(', ') : value"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-between gap-4 pt-4">
                    <span class="text-[9px] font-black text-zinc-400 dark:text-zinc-600 uppercase tracking-[0.3em]">State Persistence Bridge</span>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="open = false"
                                class="px-6 py-3 text-[10px] font-black text-zinc-500 dark:text-zinc-400 uppercase tracking-widest hover:text-zinc-900 dark:hover:text-white transition-colors">
                            Dismiss
                        </button>
                        <button type="submit"
                                :disabled="!viewName.trim()"
                                class="px-8 py-3 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-soft hover:scale-105 active:scale-95 disabled:opacity-30 disabled:hover:scale-100 transition-all">
                            Initialize Store
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
