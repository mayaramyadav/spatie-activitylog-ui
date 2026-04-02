<div class="rounded-3xl border border-slate-200 bg-white/92 p-6 shadow-soft transition-all duration-300 dark:border-slate-800 dark:bg-slate-900/88">
    <!-- Timeline Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Activity Feed</h3>
                <p class="mt-1 text-xs font-medium text-slate-500 dark:text-slate-500">
                    <span x-text="totalActivities" class="text-teal-700 dark:text-teal-300"></span> events in chronological order
                </p>
                
                <div x-show="activities.length > 0 && activities.length < totalActivities" x-cloak class="mt-3">
                    <div class="inline-flex items-center rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-amber-700 dark:border-amber-900/50 dark:bg-amber-950/20 dark:text-amber-300">
                        <svg class="w-3.5 h-3.5 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Showing recent first • Scroll for history
                    </div>
                </div>
            </div>

            <!-- Initial Load Size Selector -->
            <div class="flex items-center space-x-3 rounded-2xl border border-slate-200 bg-stone-50 p-1.5 pl-3 shadow-inner-soft dark:border-slate-700 dark:bg-slate-800/50">
                <span class="text-[11px] font-bold uppercase tracking-widest leading-none text-slate-400 dark:text-slate-500">Load</span>
                <select id="timelinePerPage"
                        x-model="perPage"
                        @change="currentPage = 1; loadActivities(1)"
                        class="cursor-pointer border-none bg-transparent py-0 pl-0 pr-8 text-sm font-bold text-slate-900 focus:ring-0 dark:text-slate-100">
                    @foreach(config('spatie-activitylog-ui.ui.per_page_options', [10, 25, 50, 100]) as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Timeline Feed -->
    <div class="relative space-y-6">
        <!-- Vertical Line -->
        <div class="absolute bottom-0 left-4 top-0 -ml-px w-0.5 bg-slate-200 dark:bg-slate-800"></div>

        <template x-for="(activity, index) in activities" :key="activity.id">
            <div class="relative pl-10 group">
                <!-- Marker -->
                <div class="absolute left-0 top-1 z-10 flex h-8 w-8 items-center justify-center rounded-full border-2 border-slate-200 bg-white transition-colors duration-300 group-hover:border-teal-400 dark:border-slate-800 dark:bg-slate-900 dark:group-hover:border-teal-500">
                    <div class="w-2 h-2 rounded-full" 
                         :class="`bg-${window.ActivityTypeStyler?.getColor(activity.event) || 'gray'}-500 group-hover:scale-125 transition-transform`"></div>
                </div>

                <!-- Content Card -->
                <div class="rounded-2xl border border-slate-100 bg-white/95 p-4 transition-all duration-300 hover:border-slate-200 hover:shadow-soft dark:border-slate-800 dark:bg-slate-900/60 dark:hover:border-slate-700">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                        <div class="flex items-center flex-wrap gap-2">
                            <span class="status-badge" :class="window.ActivityTypeStyler?.getBadgeClasses(activity.event) || 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 border-zinc-200'">
                                <span x-text="activity.event || 'system'"></span>
                            </span>
                            <span class="text-sm font-bold text-slate-900 dark:text-slate-100" x-text="activity.subject_type.split('\\').pop()"></span>
                            <span class="text-[10px] font-mono text-slate-400 dark:text-slate-600">#<span x-text="activity.subject_id"></span></span>
                        </div>
                        
                        <div class="flex items-center space-x-3 shrink-0">
                            <span class="rounded-lg bg-stone-50 px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:bg-slate-800 dark:text-slate-500"
                                  x-text="new Date(activity.created_at).toLocaleString(undefined, { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })"></span>
                            
                            <button @click="showActivityDetail(activity)" 
                                    class="rounded-xl p-2 text-slate-400 transition-all hover:bg-teal-50 hover:text-teal-700 dark:hover:bg-teal-950/20 dark:hover:text-teal-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-slate-200 bg-stone-100 dark:border-slate-700 dark:bg-slate-800">
                            <span x-show="activity.causer" class="text-xs font-black text-slate-500" x-text="activity.causer?.name?.charAt(0) || '?'"></span>
                            <svg x-show="!activity.causer" class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100" x-text="activity.description"></p>
                            <p class="mt-1 text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">
                                <span x-show="activity.causer" x-text="activity.causer?.name || 'Unknown User'"></span>
                                <span x-show="!activity.causer">System Automated</span>
                            </p>
                        </div>
                    </div>

                    <!-- Compact Attribute Preview -->
                    <div x-show="activity.properties && Object.keys(activity.properties).length > 0"
                         x-data="{ expanded: false }"
                         class="mt-4 border-t border-slate-100 pt-4 dark:border-slate-800/50">
                        <button @click="expanded = !expanded"
                                class="flex items-center text-[10px] font-black uppercase tracking-widest text-teal-700 transition-colors hover:text-teal-800 dark:text-teal-300">
                            <svg :class="expanded ? 'rotate-90' : ''" class="w-3 h-3 mr-1 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                            <span x-text="expanded ? 'Hide' : 'Visualise'"></span> Changes
                        </button>

                        <div x-show="expanded" x-collapse class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <template x-if="activity.properties.old">
                                <div class="space-y-2">
                                    <h5 class="text-[9px] font-black uppercase tracking-[0.2em] text-red-500/70 dark:text-red-400/50 flex items-center">
                                        <div class="w-1 h-1 bg-current rounded-full mr-2"></div> Previous
                                    </h5>
                                    <div class="bg-red-50/50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-xl p-3">
                                        <pre class="text-[10px] font-mono text-red-800 dark:text-red-300 leading-relaxed custom-scrollbar opacity-80" 
                                             x-text="JSON.stringify(activity.properties.old, null, 2)"></pre>
                                    </div>
                                </div>
                            </template>
                            
                            <template x-if="activity.properties.attributes">
                                <div class="space-y-2">
                                    <h5 class="text-[9px] font-black uppercase tracking-[0.2em] text-emerald-500/70 dark:text-emerald-400/50 flex items-center">
                                        <div class="w-1 h-1 bg-current rounded-full mr-2"></div> Updated
                                    </h5>
                                    <div class="bg-emerald-50/50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-900/30 rounded-xl p-3">
                                        <pre class="text-[10px] font-mono text-emerald-800 dark:text-emerald-300 leading-relaxed custom-scrollbar opacity-80" 
                                             x-text="JSON.stringify(activity.properties.attributes, null, 2)"></pre>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="activities.length === 0" x-cloak class="text-center py-20">
        <div class="mx-auto mb-6 inline-flex h-20 w-20 items-center justify-center rounded-3xl border border-slate-100 bg-stone-100 dark:border-slate-700 dark:bg-slate-800">
            <svg class="h-10 w-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h3 class="mb-2 text-xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Timeline is empty</h3>
        <p class="mx-auto max-w-xs text-sm font-medium text-slate-500 dark:text-slate-400">No records match your criteria. Try adjusting your parameters.</p>
    </div>

    <!-- Load More -->
    <div x-show="currentPage < totalPages" x-cloak class="mt-12 text-center">
        <button @click="loadMoreActivities()" :disabled="loading"
                class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-8 py-3.5 text-xs font-black uppercase tracking-widest text-slate-900 shadow-soft transition-all hover:border-teal-300 active:scale-95 disabled:opacity-50 group dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:hover:border-teal-700">
            <template x-if="loading">
                <svg class="animate-spin -ml-1 mr-3 h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </template>
            <template x-if="!loading">
                <svg class="w-4 h-4 mr-2 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
            </template>
            <span x-text="loading ? 'Retrieving Records...' : 'Explore History'"></span>
        </button>

        <!-- Progress Indicator -->
        <div class="mt-8 max-w-sm mx-auto">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">Fetched <span class="text-slate-900 dark:text-slate-100" x-text="activities.length"></span> of <span x-text="totalActivities"></span></span>
                <span class="text-[10px] font-black text-teal-700 dark:text-teal-300" x-text="Math.round((activities.length / totalActivities) * 100) + '%'"></span>
            </div>
            <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                <div class="h-full bg-teal-600 transition-all duration-500 dark:bg-teal-500" :style="`width: ${ (activities.length / totalActivities) * 100 }%`"></div>
            </div>
        </div>
    </div>

    <!-- All Caught Up -->
    <div x-show="activities.length > 0 && currentPage >= totalPages" x-cloak class="mt-12 text-center">
        <div class="inline-flex items-center px-4 py-2 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-[11px] font-bold uppercase tracking-widest">History fully retrieved</span>
        </div>
    </div>
</div>
