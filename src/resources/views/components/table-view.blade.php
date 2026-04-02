<div class="overflow-hidden rounded-3xl border border-slate-200 bg-white/92 shadow-soft transition-all duration-300 dark:border-slate-800 dark:bg-slate-900/88">
    <!-- Table Header -->
    <div class="border-b border-slate-200 bg-stone-50/85 px-6 py-5 dark:border-slate-800 dark:bg-slate-800/35">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold tracking-tight text-slate-900 dark:text-slate-100">Recent Activity Log</h3>
                <p class="mt-0.5 text-xs font-medium text-slate-500 dark:text-slate-500">
                    <span x-text="totalActivities" class="text-teal-700 dark:text-teal-300"></span> total events recorded
                    <span x-show="hasActiveFilters" x-cloak class="ml-1 rounded px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-teal-50 text-teal-700 dark:bg-teal-950/30 dark:text-teal-300">Filtered</span>
                </p>
            </div>

            <!-- Per Page Selector -->
            <div class="flex items-center space-x-3 rounded-2xl border border-slate-200 bg-white px-3 py-1.5 shadow-inner-soft dark:border-slate-700 dark:bg-slate-900">
                <span class="text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Show</span>
                <select id="perPage"
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

    <!-- Table Container -->
    <div class="overflow-x-auto custom-scrollbar">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
            <thead>
                <tr class="bg-stone-50/70 dark:bg-slate-900/60">
                    <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Event</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Description</th>
                    <th class="hidden px-6 py-4 text-left text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 md:table-cell">Subject</th>
                    <th class="hidden px-6 py-4 text-left text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 lg:table-cell">Initiated By</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Timestamp</th>
                    <th class="px-6 py-4 text-right text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 transition-opacity duration-300 dark:divide-slate-800" :class="{ 'opacity-50': loading }">
                <template x-for="activity in activities" :key="activity.id">
                    <tr class="group transition-colors duration-200 hover:bg-stone-50/80 dark:hover:bg-slate-800/45">
                        <!-- Event Type Badge -->
                        <td class="px-6 py-4 whitespace-nowrap">
                                <span class="status-badge" :class="window.ActivityTypeStyler?.getBadgeClasses(activity.event) || 'bg-slate-100 text-slate-800 border-slate-200'">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full"
                                      :class="`bg-${window.ActivityTypeStyler?.getColor(activity.event) || 'gray'}-500`"></span>
                                <span x-text="activity.event || 'system'"></span>
                            </span>
                        </td>

                        <!-- Description & Context -->
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-slate-900 dark:text-slate-100" x-text="activity.description"></span>
                                <div class="mt-1 flex items-center space-x-2 text-[10px] font-medium text-slate-500 lg:hidden">
                                    <span class="rounded bg-stone-100 px-1.5 py-0.5 dark:bg-slate-800" x-text="activity.subject_type.split('\\').pop()"></span>
                                    <span class="text-slate-300 dark:text-slate-700">•</span>
                                    <span x-text="activity.causer?.name || 'System'"></span>
                                </div>
                            </div>
                        </td>

                        <!-- Subject Info -->
                        <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="mb-0.5 text-xs font-bold text-slate-900 dark:text-slate-100" x-text="activity.subject_type.split('\\').pop()"></span>
                                <span class="cursor-pointer text-[10px] font-mono text-slate-400 transition-colors group-hover:text-teal-700 dark:text-slate-500 dark:group-hover:text-teal-300" 
                                      @click.stop="navigator.clipboard.writeText(activity.subject_id); window.notify.success('Copied', 'ID copied to clipboard')"
                                      title="Click to copy ID">
                                    #<span x-text="activity.subject_id"></span>
                                </span>
                            </div>
                        </td>

                        <!-- Causer Info -->
                        <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap">
                            <div x-show="activity.causer" class="flex items-center">
                                <div class="relative flex h-8 w-8 items-center justify-center overflow-hidden rounded-full border border-slate-200 bg-stone-100 transition-transform duration-300 group-hover:scale-110 dark:border-slate-700 dark:bg-slate-800">
                                    <span class="text-[10px] font-black text-slate-600 dark:text-slate-400" x-text="activity.causer?.name?.charAt(0) || '?'"></span>
                                </div>
                                <div class="ml-3 flex flex-col">
                                    <span class="text-xs font-bold text-slate-900 dark:text-slate-200" x-text="activity.causer?.name || 'Unknown'"></span>
                                    <span class="text-[10px] font-medium text-slate-500 dark:text-slate-500" x-text="activity.causer?.email || ''"></span>
                                </div>
                            </div>
                            <div x-show="!activity.causer" class="flex items-center text-slate-400 dark:text-slate-600">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-dashed border-slate-200 opacity-50 dark:border-slate-800">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                                </div>
                                <span class="ml-3 text-[11px] font-bold uppercase tracking-wider opacity-60">System</span>
                            </div>
                        </td>

                        <!-- Timestamp -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col text-right sm:text-left">
                                <span class="text-xs font-bold text-slate-900 dark:text-slate-200" x-text="new Date(activity.created_at).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' })"></span>
                                <span class="mt-0.5 text-[10px] font-medium text-slate-500 dark:text-slate-500" x-text="new Date(activity.created_at).toLocaleTimeString(undefined, { hour: '2-digit', minute: '2-digit', second: '2-digit' })"></span>
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <button @click="showActivityDetail(activity)"
                                    class="inline-flex items-center rounded-lg px-3 py-1.5 text-[11px] font-black uppercase tracking-widest text-teal-700 transition-all duration-200 hover:bg-teal-50 dark:text-teal-300 dark:hover:bg-teal-950/30">
                                Details <svg class="ml-1.5 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"></path></svg>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        <!-- Empty State -->
        <div x-show="!loading && activities.length === 0" x-cloak
             class="bg-white py-20 text-center dark:bg-slate-900">
            <div class="mx-auto mb-6 inline-flex h-20 w-20 items-center justify-center rounded-3xl border border-slate-100 bg-stone-50 dark:border-slate-800 dark:bg-slate-800/50">
                <svg class="h-10 w-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
            </div>
            <h3 class="mb-2 text-xl font-bold tracking-tight text-slate-900 dark:text-slate-100">No activities found</h3>
            <p class="mx-auto max-w-xs text-sm font-medium text-slate-500 dark:text-slate-400">Try adjusting your filters or search terms to find what you're looking for.</p>
            <button @click="$dispatch('clear-filters')"
                    class="mt-6 inline-flex items-center rounded-xl bg-teal-600 px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-lg shadow-teal-500/20 transition-all hover:bg-teal-700 active:scale-95">
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
         class="border-t border-slate-200 bg-stone-50/85 px-6 py-5 dark:border-slate-800 dark:bg-slate-800/35">
         
         <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
             <!-- Results Info -->
             <div class="order-2 sm:order-1">
                 <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">
                     Page <span class="text-slate-900 dark:text-slate-100" x-text="currentPage"></span> of <span class="text-slate-900 dark:text-slate-100" x-text="totalPages"></span>
                 </p>
             </div>

             <!-- Desktop Pagination Controls -->
             <div class="order-1 sm:order-2 flex items-center space-x-1.5">
                 <button @click="goToPage(currentPage - 1)" :disabled="currentPage <= 1 || loadingPage"
                         class="rounded-xl border border-slate-200 bg-white p-2 transition-all hover:border-teal-300 disabled:opacity-30 dark:border-slate-700 dark:bg-slate-800 dark:hover:border-teal-700">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                 </button>

                 <template x-for="page in getPageNumbers()" :key="page">
                     <div class="flex items-center">
                         <template x-if="typeof page === 'number'">
                             <button @click="goToPage(page)"
                                     :class="currentPage === page ? 'bg-teal-600 text-white shadow-lg shadow-teal-500/25 border-teal-500 font-black' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:border-teal-300 dark:hover:border-teal-700 font-bold'"
                                     class="w-10 h-10 text-[11px] rounded-xl border transition-all duration-200"
                                     x-text="page"></button>
                         </template>
                         <template x-if="typeof page === 'string'">
                             <span class="select-none px-2 font-bold text-slate-400">...</span>
                         </template>
                     </div>
                 </template>

                 <button @click="goToPage(currentPage + 1)" :disabled="currentPage >= totalPages || loadingPage"
                         class="rounded-xl border border-slate-200 bg-white p-2 transition-all hover:border-teal-300 disabled:opacity-30 dark:border-slate-700 dark:bg-slate-800 dark:hover:border-teal-700">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                 </button>
             </div>

             <!-- Jump to page -->
             <div class="order-3 flex items-center space-x-2 rounded-xl border border-slate-200 bg-white p-1 shadow-inner-soft dark:border-slate-700 dark:bg-slate-900">
                 <input type="number" x-model="pageInput" @keydown.enter="goToPage(pageInput)"
                        class="w-12 border-none bg-transparent py-1 text-center text-xs font-black text-slate-900 focus:ring-0 dark:text-slate-100"
                        placeholder="Go" min="1" :max="totalPages">
                 <button @click="goToPage(pageInput)" class="rounded-lg bg-teal-600 p-1.5 text-white transition-colors hover:bg-teal-700">
                     <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7-7 7"></path></svg>
                 </button>
             </div>
         </div>
    </div>
</div>
