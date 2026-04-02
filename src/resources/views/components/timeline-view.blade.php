<div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-soft p-6 border border-zinc-200 dark:border-zinc-800 transition-all duration-300">
    <!-- Timeline Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold text-zinc-900 dark:text-white tracking-tight">Activity Feed</h3>
                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-500 font-medium">
                    <span x-text="totalActivities" class="text-indigo-600 dark:text-indigo-400"></span> events in chronological order
                </p>
                
                <div x-show="activities.length > 0 && activities.length < totalActivities" x-cloak class="mt-3">
                    <div class="inline-flex items-center px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/50 text-amber-700 dark:text-amber-400 text-[10px] font-bold uppercase tracking-wider">
                        <svg class="w-3.5 h-3.5 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Showing recent first • Scroll for history
                    </div>
                </div>
            </div>

            <!-- Initial Load Size Selector -->
            <div class="flex items-center space-x-3 bg-zinc-50 dark:bg-zinc-800/50 p-1.5 pl-3 rounded-xl border border-zinc-100 dark:border-zinc-700 shadow-inner-soft">
                <span class="text-[11px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest leading-none">Load</span>
                <select id="timelinePerPage"
                        x-model="perPage"
                        @change="currentPage = 1; loadActivities(1)"
                        class="border-none bg-transparent py-0 pl-0 pr-8 text-sm font-bold text-zinc-900 dark:text-white focus:ring-0 cursor-pointer">
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
        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-zinc-100 dark:bg-zinc-800 -ml-px"></div>

        <template x-for="(activity, index) in activities" :key="activity.id">
            <div class="relative pl-10 group">
                <!-- Marker -->
                <div class="absolute left-0 top-1 w-8 h-8 rounded-full bg-white dark:bg-zinc-900 border-2 border-zinc-100 dark:border-zinc-800 flex items-center justify-center z-10 group-hover:border-indigo-500 dark:group-hover:border-indigo-400 transition-colors duration-300">
                    <div class="w-2 h-2 rounded-full" 
                         :class="`bg-${window.ActivityTypeStyler?.getColor(activity.event) || 'gray'}-500 group-hover:scale-125 transition-transform`"></div>
                </div>

                <!-- Content Card -->
                <div class="bg-white dark:bg-zinc-900/50 rounded-2xl border border-zinc-100 dark:border-zinc-800 p-4 hover:border-zinc-200 dark:hover:border-zinc-700 hover:shadow-soft transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                        <div class="flex items-center flex-wrap gap-2">
                            <span class="status-badge" :class="window.ActivityTypeStyler?.getBadgeClasses(activity.event) || 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 border-zinc-200'">
                                <span x-text="activity.event || 'system'"></span>
                            </span>
                            <span class="text-sm font-bold text-zinc-900 dark:text-zinc-100" x-text="activity.subject_type.split('\\').pop()"></span>
                            <span class="text-[10px] font-mono text-zinc-400 dark:text-zinc-600">#<span x-text="activity.subject_id"></span></span>
                        </div>
                        
                        <div class="flex items-center space-x-3 shrink-0">
                            <span class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider bg-zinc-50 dark:bg-zinc-800 px-2 py-1 rounded-lg"
                                  x-text="new Date(activity.created_at).toLocaleString(undefined, { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })"></span>
                            
                            <button @click="showActivityDetail(activity)" 
                                    class="p-2 text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="h-10 w-10 shrink-0 rounded-xl bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 flex items-center justify-center overflow-hidden">
                            <span x-show="activity.causer" class="text-xs font-black text-zinc-500" x-text="activity.causer?.name?.charAt(0) || '?'"></span>
                            <svg x-show="!activity.causer" class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100" x-text="activity.description"></p>
                            <p class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mt-1">
                                <span x-show="activity.causer" x-text="activity.causer?.name || 'Unknown User'"></span>
                                <span x-show="!activity.causer">System Automated</span>
                            </p>
                        </div>
                    </div>

                    <!-- Compact Attribute Preview -->
                    <div x-show="activity.attribute_changes && Object.keys(activity.attribute_changes).length > 0"
                         x-data="{ expanded: false }"
                         class="mt-4 pt-4 border-t border-zinc-50 dark:border-zinc-800/50">
                        <button @click="expanded = !expanded"
                                class="flex items-center text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 transition-colors">
                            <svg :class="expanded ? 'rotate-90' : ''" class="w-3 h-3 mr-1 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                            <span x-text="expanded ? 'Hide' : 'Visualise'"></span> Changes
                        </button>

                        <div x-show="expanded" x-collapse class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <template x-if="activity.attribute_changes.old">
                                <div class="space-y-2">
                                    <h5 class="text-[9px] font-black uppercase tracking-[0.2em] text-red-500/70 dark:text-red-400/50 flex items-center">
                                        <div class="w-1 h-1 bg-current rounded-full mr-2"></div> Previous
                                    </h5>
                                    <div class="bg-red-50/50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-xl p-3">
                                        <pre class="text-[10px] font-mono text-red-800 dark:text-red-300 leading-relaxed custom-scrollbar opacity-80" 
                                             x-text="JSON.stringify(activity.attribute_changes.old, null, 2)"></pre>
                                    </div>
                                </div>
                            </template>
                            
                            <template x-if="activity.attribute_changes.attributes">
                                <div class="space-y-2">
                                    <h5 class="text-[9px] font-black uppercase tracking-[0.2em] text-emerald-500/70 dark:text-emerald-400/50 flex items-center">
                                        <div class="w-1 h-1 bg-current rounded-full mr-2"></div> Updated
                                    </h5>
                                    <div class="bg-emerald-50/50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-900/30 rounded-xl p-3">
                                        <pre class="text-[10px] font-mono text-emerald-800 dark:text-emerald-300 leading-relaxed custom-scrollbar opacity-80" 
                                             x-text="JSON.stringify(activity.attribute_changes.attributes, null, 2)"></pre>
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
        <div class="inline-flex items-center justify-center w-20 h-20 mx-auto mb-6 bg-zinc-100 dark:bg-zinc-800 rounded-3xl border border-zinc-100 dark:border-zinc-700">
            <svg class="w-10 h-10 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2 tracking-tight">Timeline is empty</h3>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 max-w-xs mx-auto font-medium">No records match your criteria. Try adjusting your parameters.</p>
    </div>

    <!-- Load More -->
    <div x-show="currentPage < totalPages" x-cloak class="mt-12 text-center">
        <button @click="loadMoreActivities()" :disabled="loading"
                class="inline-flex items-center px-8 py-3.5 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-xs font-black uppercase tracking-widest text-zinc-900 dark:text-white rounded-2xl shadow-soft hover:border-indigo-500 dark:hover:border-indigo-400 transition-all active:scale-95 disabled:opacity-50 group">
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
                <span class="text-[10px] font-black uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Fetched <span class="text-zinc-900 dark:text-white" x-text="activities.length"></span> of <span x-text="totalActivities"></span></span>
                <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400" x-text="Math.round((activities.length / totalActivities) * 100) + '%'"></span>
            </div>
            <div class="h-1.5 w-full bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                <div class="h-full bg-indigo-600 dark:bg-indigo-500 transition-all duration-500" :style="`width: ${ (activities.length / totalActivities) * 100 }%`"></div>
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
