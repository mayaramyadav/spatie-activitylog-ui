<div x-data="{ open: false, activity: null }"
     x-on:show-activity-detail.window="activity = $event.detail; open = true"
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
    <div class="flex min-h-full items-center justify-center p-4 sm:p-6 lg:p-8">
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
             class="relative w-full max-w-5xl bg-white dark:bg-zinc-900 rounded-[2rem] shadow-soft border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            
            <!-- Modal Header -->
            <div class="relative px-8 py-6 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner-soft border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800"
                             x-show="activity"
                             :class="window.ActivityTypeStyler?.getTimelineClasses(activity?.event) || ''">
                            <svg class="w-7 h-7 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="window.ActivityTypeStyler?.getIcon(activity?.event) || 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-zinc-900 dark:text-white uppercase tracking-tight">Event Insight</h3>
                            <p class="text-xs font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mt-0.5" x-text="activity?.description || 'Loading documentation...'"></p>
                        </div>
                    </div>

                    <button @click="open = false" 
                            class="w-10 h-10 rounded-xl flex items-center justify-center text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                <template x-if="activity">
                    <div class="space-y-10">
                        <!-- Primary Metadata -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="space-y-1">
                                <span class="text-[10px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.2em]">Occurrence</span>
                                <p class="text-sm font-black text-zinc-900 dark:text-white" x-text="new Date(activity.created_at).toLocaleString(undefined, { dateStyle: 'long', timeStyle: 'short' })"></p>
                            </div>
                            <div class="space-y-1">
                                <span class="text-[10px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.2em]">Initiator</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-md bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center border border-zinc-200 dark:border-zinc-700">
                                        <span class="text-[9px] font-black text-zinc-500" x-text="activity.causer?.name?.charAt(0) || 'S'"></span>
                                    </div>
                                    <p class="text-sm font-black text-zinc-900 dark:text-white" x-text="activity.causer?.name || 'System Automated'"></p>
                                </div>
                            </div>
                            <div class="space-y-1 text-right">
                                <span class="text-[10px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.2em]">Signature</span>
                                <div class="inline-flex items-center px-3 py-1 bg-zinc-900 dark:bg-indigo-500 text-white dark:text-white rounded-full">
                                    <span class="text-[9px] font-black uppercase tracking-widest" x-text="activity.event || 'system'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Subject Link -->
                        <div class="p-6 bg-zinc-50 dark:bg-zinc-800/30 rounded-2xl border border-zinc-100 dark:border-zinc-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-white dark:bg-zinc-900 flex items-center justify-center border border-zinc-200 dark:border-zinc-800">
                                        <svg class="w-6 h-6 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    </div>
                                    <div>
                                        <span class="text-[9px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Resource Subject</span>
                                        <p class="text-sm font-black text-zinc-900 dark:text-white" x-text="activity.subject_type"></p>
                                    </div>
                                </div>
                                <div class="px-4 py-2 bg-zinc-200 dark:bg-zinc-700/50 rounded-xl text-[10px] font-black font-mono text-zinc-600 dark:text-zinc-400">
                                    ID: <span x-text="activity.subject_id"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Detailed Diff -->
                        <div x-show="activity.properties && Object.keys(activity.properties).length > 0" class="space-y-6">
                            <div class="flex items-center gap-4">
                                <h4 class="text-xs font-black text-zinc-900 dark:text-white uppercase tracking-[0.2em]">State Transformation</h4>
                                <div class="h-px flex-1 bg-zinc-100 dark:bg-zinc-800"></div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <template x-if="activity.properties.old">
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between px-2">
                                            <span class="text-[10px] font-black text-red-500 dark:text-red-400 uppercase tracking-widest flex items-center">
                                                <div class="w-1.5 h-1.5 bg-current rounded-full mr-2"></div> Previous state
                                            </span>
                                            <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-tighter">Read-only Snapshot</span>
                                        </div>
                                        <div class="relative bg-zinc-900 rounded-3xl p-6 border border-zinc-800 overflow-hidden group">
                                            <div class="absolute inset-0 bg-red-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                            <pre class="text-[11px] font-mono text-red-400/80 leading-relaxed custom-scrollbar relative z-10" 
                                                 x-text="JSON.stringify(activity.properties.old, null, 2)"></pre>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="activity.properties.attributes">
                                    <div class="space-y-4 font-inter">
                                        <div class="flex items-center justify-between px-2">
                                            <span class="text-[10px] font-black text-emerald-500 dark:text-emerald-400 uppercase tracking-widest flex items-center">
                                                <div class="w-1.5 h-1.5 bg-current rounded-full mr-2"></div> Evolved state
                                            </span>
                                            <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-tighter">Updated Values</span>
                                        </div>
                                        <div class="relative bg-zinc-900 rounded-3xl p-6 border border-zinc-800 overflow-hidden group">
                                            <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                            <pre class="text-[11px] font-mono text-emerald-400/80 leading-relaxed custom-scrollbar relative z-10" 
                                                 x-text="JSON.stringify(activity.properties.attributes, null, 2)"></pre>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Fallback for generic metadata/properties -->
                            <template x-if="!activity.properties.old && !activity.properties.attributes">
                                <div class="bg-zinc-900 rounded-3xl p-6 border border-zinc-800">
                                    <pre class="text-[11px] font-mono text-zinc-400 leading-relaxed" 
                                         x-text="JSON.stringify(activity.properties, null, 2)"></pre>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Modal Footer -->
            <div class="px-8 py-6 bg-zinc-50/50 dark:bg-zinc-900/50 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold text-zinc-400 dark:text-zinc-600 uppercase tracking-widest">Global Telemetry Signature</span>
                </div>
                
                <button @click="open = false" 
                        class="px-8 py-3 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-soft hover:scale-105 active:scale-95 transition-all">
                    Acknowledge
                </button>
            </div>
        </div>
    </div>
</div>
