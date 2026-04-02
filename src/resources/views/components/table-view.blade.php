<div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-soft border border-zinc-200 dark:border-zinc-800 overflow-hidden transition-all duration-300">
    <!-- Table Header -->
    <div class="px-6 py-5 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/30">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white tracking-tight">Recent Activity Log</h3>
                <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-500 font-medium">
                    <span x-text="totalActivities" class="text-indigo-600 dark:text-indigo-400"></span> total events recorded
                    <span x-show="hasActiveFilters" x-cloak class="ml-1 px-1.5 py-0.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded text-[10px] font-bold uppercase tracking-wider">Filtered</span>
                </p>
            </div>

            <!-- Per Page Selector -->
            <div class="flex items-center space-x-3 bg-white dark:bg-zinc-900 p-1.5 pl-3 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-inner-soft">
                <span class="text-[11px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Show</span>
                <select id="perPage"
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

    <!-- Table Container -->
    <div class="overflow-x-auto custom-scrollbar">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
            <thead>
                <tr class="bg-zinc-50/50 dark:bg-zinc-900/50">
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Event</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Description</th>
                    <th class="hidden md:table-cell px-6 py-4 text-left text-[11px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Subject</th>
                    <th class="hidden lg:table-cell px-6 py-4 text-left text-[11px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Initiated By</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Timestamp</th>
                    <th class="px-6 py-4 text-right text-[11px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800 transition-opacity duration-300" :class="{ 'opacity-50': loading }">
                <template x-for="activity in activities" :key="activity.id">
                    <tr class="group hover:bg-zinc-50/80 dark:hover:bg-zinc-800/40 transition-colors duration-200">
                        <!-- Event Type Badge -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="status-badge" :class="window.ActivityTypeStyler?.getBadgeClasses(activity.event) || 'bg-zinc-100 text-zinc-800 border-zinc-200'">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full"
                                      :class="`bg-${window.ActivityTypeStyler?.getColor(activity.event) || 'gray'}-500`"></span>
                                <span x-text="activity.event || 'system'"></span>
                            </span>
                        </td>

                        <!-- Description & Context -->
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100" x-text="activity.description"></span>
                                <!-- Mobile context -->
                                <div class="lg:hidden mt-1 flex items-center space-x-2 text-[10px] font-medium text-zinc-500">
                                    <span class="bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded" x-text="activity.subject_type.split('\\').pop()"></span>
                                    <span class="text-zinc-300 dark:text-zinc-700">•</span>
                                    <span x-text="activity.causer?.name || 'System'"></span>
                                </div>
                            </div>
                        </td>

                        <!-- Subject Info -->
                        <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-zinc-900 dark:text-zinc-100 mb-0.5" x-text="activity.subject_type.split('\\').pop()"></span>
                                <span class="group-hover:text-indigo-600 dark:group-hover:text-indigo-400 text-[10px] font-mono text-zinc-400 dark:text-zinc-500 transition-colors cursor-pointer" 
                                      @click.stop="navigator.clipboard.writeText(activity.subject_id); window.notify.success('Copied', 'ID copied to clipboard')"
                                      title="Click to copy ID">
                                    #<span x-text="activity.subject_id"></span>
                                </span>
                            </div>
                        </td>

                        <!-- Causer Info -->
                        <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap">
                            <div x-show="activity.causer" class="flex items-center">
                                <div class="relative h-8 w-8 rounded-full bg-zinc-200 dark:bg-zinc-800 flex items-center justify-center border border-zinc-100 dark:border-zinc-700 overflow-hidden group-hover:scale-110 transition-transform duration-300">
                                    <span class="text-[10px] font-black text-zinc-600 dark:text-zinc-400" x-text="activity.causer?.name?.charAt(0) || '?'"></span>
                                </div>
                                <div class="ml-3 flex flex-col">
                                    <span class="text-xs font-bold text-zinc-900 dark:text-zinc-200" x-text="activity.causer?.name || 'Unknown'"></span>
                                    <span class="text-[10px] text-zinc-500 dark:text-zinc-500 font-medium" x-text="activity.causer?.email || ''"></span>
                                </div>
                            </div>
                            <div x-show="!activity.causer" class="flex items-center text-zinc-400 dark:text-zinc-600">
                                <div class="h-8 w-8 rounded-full border-2 border-dashed border-zinc-200 dark:border-zinc-800 flex items-center justify-center opacity-50">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                                </div>
                                <span class="ml-3 text-[11px] font-bold uppercase tracking-wider opacity-60">System</span>
                            </div>
                        </td>

                        <!-- Timestamp -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col text-right sm:text-left">
                                <span class="text-xs font-bold text-zinc-900 dark:text-zinc-200" x-text="new Date(activity.created_at).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' })"></span>
                                <span class="text-[10px] text-zinc-500 dark:text-zinc-500 font-medium mt-0.5" x-text="new Date(activity.created_at).toLocaleTimeString(undefined, { hour: '2-digit', minute: '2-digit', second: '2-digit' })"></span>
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <button @click="showActivityDetail(activity)"
                                    class="inline-flex items-center px-3 py-1.5 text-[11px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition-all duration-200">
                                Details <svg class="ml-1.5 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"></path></svg>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        <!-- Empty State -->
        <div x-show="!loading && activities.length === 0" x-cloak
             class="text-center py-20 bg-white dark:bg-zinc-900">
            <div class="inline-flex items-center justify-center w-20 h-20 mx-auto mb-6 bg-zinc-50 dark:bg-zinc-800/50 rounded-3xl border border-zinc-100 dark:border-zinc-800">
                <svg class="w-10 h-10 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2 tracking-tight">No activities found</h3>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 max-w-xs mx-auto font-medium">Try adjusting your filters or search terms to find what you're looking for.</p>
            <button @click="$dispatch('clear-filters')"
                    class="mt-6 inline-flex items-center px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-500/20 transition-all active:scale-95">
                Clear All Filters
            </button>
        </div>
    </div>

    <!-- Pagination Container -->
    <div x-show="totalPages > 1" x-cloak
         x-data="{
             pageInput: currentPage,
             loadingPage: false,
             getPageNumbers() {
                 const pages = [];
                 const sideNumbers = 2;
                 pages.push(1);
                 let start = Math.max(2, currentPage - sideNumbers);
                 let end = Math.min(totalPages - 1, currentPage + sideNumbers);
                 if (start > 2) pages.push('dots1');
                 for (let i = start; i <= end; i++) pages.push(i);
                 if (end < totalPages - 1) pages.push('dots2');
                 if (totalPages > 1) pages.push(totalPages);
                 return pages;
             },
             async goToPage(page) {
                 const targetPage = parseInt(page);
                 if (isNaN(targetPage) || targetPage < 1 || targetPage > totalPages || targetPage === currentPage) {
                     this.pageInput = currentPage;
                     return;
                 }
                 this.loadingPage = true;
                 try {
                     loadActivities(targetPage);
                     await new Promise(r => setTimeout(r, 400));
                 } finally {
                     this.loadingPage = false;
                     this.pageInput = currentPage;
                 }
             }
         }"
         class="px-6 py-5 bg-zinc-50/50 dark:bg-zinc-800/30 border-t border-zinc-100 dark:border-zinc-800">
         
         <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
             <!-- Results Info -->
             <div class="order-2 sm:order-1">
                 <p class="text-xs font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                     Page <span class="text-zinc-900 dark:text-white" x-text="currentPage"></span> of <span class="text-zinc-900 dark:text-white" x-text="totalPages"></span>
                 </p>
             </div>

             <!-- Desktop Pagination Controls -->
             <div class="order-1 sm:order-2 flex items-center space-x-1.5">
                 <button @click="goToPage(currentPage - 1)" :disabled="currentPage <= 1 || loadingPage"
                         class="p-2 border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl hover:border-indigo-400 dark:hover:border-zinc-600 disabled:opacity-30 transition-all">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                 </button>

                 <template x-for="page in getPageNumbers()" :key="page">
                     <div class="flex items-center">
                         <template x-if="typeof page === 'number'">
                             <button @click="goToPage(page)"
                                     :class="currentPage === page ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25 border-indigo-500 font-black' : 'bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 border-zinc-200 dark:border-zinc-700 hover:border-indigo-400 dark:hover:border-zinc-500 font-bold'"
                                     class="w-10 h-10 text-[11px] rounded-xl border transition-all duration-200"
                                     x-text="page"></button>
                         </template>
                         <template x-if="typeof page === 'string'">
                             <span class="px-2 text-zinc-400 select-none font-bold">...</span>
                         </template>
                     </div>
                 </template>

                 <button @click="goToPage(currentPage + 1)" :disabled="currentPage >= totalPages || loadingPage"
                         class="p-2 border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl hover:border-indigo-400 dark:hover:border-zinc-600 disabled:opacity-30 transition-all">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                 </button>
             </div>

             <!-- Jump to page -->
             <div class="order-3 flex items-center space-x-2 bg-white dark:bg-zinc-900 p-1 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-inner-soft">
                 <input type="number" x-model="pageInput" @keydown.enter="goToPage(pageInput)"
                        class="w-12 border-none bg-transparent py-1 text-center text-xs font-black text-zinc-900 dark:text-white focus:ring-0"
                        placeholder="Go" min="1" :max="totalPages">
                 <button @click="goToPage(pageInput)" class="p-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                     <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7-7 7"></path></svg>
                 </button>
             </div>
         </div>
    </div>
</div>
