@extends('spatie-activitylog-ui::layouts.app')

@section('title')
    {{ config('spatie-activitylog-ui.ui.title', 'Activity Log') }} Dashboard
@endsection

@section('content')
    <div x-data="activityDashboard()" x-init="init()" class="space-y-8 animate-in fade-in duration-700">
        <!-- Modern Header -->
        <div class="flex flex-col gap-4 border-b border-slate-200 dark:border-slate-800 pb-6 md:flex-row md:items-end md:justify-between">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-xs font-medium text-slate-500 dark:text-slate-500">
                        <li><a href="#" class="transition-colors hover:text-teal-600 dark:hover:text-teal-400">Dashboard</a></li>
                        <li>
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                        </li>
                        <li class="text-slate-900 dark:text-slate-200">Activity Logs</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">
                    {{ config('spatie-activitylog-ui.ui.title', 'Activity Log') }}
                </h1>
                <p class="mt-2 max-w-2xl text-sm leading-7 text-slate-500 dark:text-slate-400">
                    Comprehensive audit trail and system activity monitoring with real-time analytics and advanced filtering.
                </p>
            </div>

            <!-- Global Actions & View Switcher -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Export Action -->
                @if(config('spatie-activitylog-ui.features.exports', true))
                    <button @click="exportActivities()"
                        class="inline-flex items-center rounded-2xl border border-slate-200 bg-white/90 px-4 py-2 text-sm font-semibold text-slate-700 shadow-soft transition-all duration-200 hover:border-teal-300 hover:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/30 dark:border-slate-700 dark:bg-slate-900/80 dark:text-slate-200 dark:hover:border-teal-700 dark:hover:bg-slate-800">
                        <svg class="mr-2 h-4.5 w-4.5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M7 7h10a2 2 0 012 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z"></path>
                        </svg>
                        Export Data
                    </button>
                @endif

                <!-- Segmented Control View Switcher -->
                <div class="inline-flex rounded-2xl border border-slate-200 bg-white/75 p-1 shadow-inner-soft dark:border-slate-800 dark:bg-slate-900/75">
                    <button @click="switchView('table')"
                        :class="currentView === 'table' ? 'bg-stone-50 dark:bg-slate-800 text-teal-700 dark:text-teal-300 shadow-soft' : 'text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                        class="relative flex items-center px-4 py-1.5 text-xs font-bold rounded-lg transition-all duration-300 group">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18m-9 8h9"></path>
                        </svg>
                        Table
                    </button>
                    <button @click="switchView('timeline')"
                        :class="currentView === 'timeline' ? 'bg-stone-50 dark:bg-slate-800 text-teal-700 dark:text-teal-300 shadow-soft' : 'text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                        class="relative flex items-center px-4 py-1.5 text-xs font-bold rounded-lg transition-all duration-300 group ml-1">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Timeline
                    </button>
                    @if(config('spatie-activitylog-ui.features.analytics', true))
                        <button @click="switchView('analytics')"
                            :class="currentView === 'analytics' ? 'bg-stone-50 dark:bg-slate-800 text-teal-700 dark:text-teal-300 shadow-soft' : 'text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                            class="relative flex items-center px-4 py-1.5 text-xs font-bold rounded-lg transition-all duration-300 group ml-1">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Analytics
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filter Panel -->
            <aside x-show="currentView !== 'analytics'" 
                   x-transition:enter="transition ease-out duration-300"
                   x-transition:enter-start="opacity-0 -translate-x-4"
                   x-transition:enter-end="opacity-100 translate-x-0"
                   class="w-full lg:w-80 lg:flex-shrink-0">
                <div class="sticky top-24">
                    @include('spatie-activitylog-ui::components.filter-panel')
                </div>
            </aside>

            <!-- Main Content Grid -->
            <main class="w-full lg:flex-1 lg:min-w-0" :class="{ 'lg:ml-0': currentView === 'analytics' }">
                <!-- Advanced Loading Placeholder (Skeleton-like) -->
                <div x-show="loading" class="space-y-4 py-4">
                    <div class="h-10 w-full animate-pulse rounded-xl bg-slate-200 dark:bg-slate-800"></div>
                    <div class="grid grid-cols-4 gap-4">
                        <div class="h-32 animate-pulse rounded-xl bg-stone-100 dark:bg-slate-900"></div>
                        <div class="h-32 animate-pulse rounded-xl bg-stone-100 dark:bg-slate-900"></div>
                        <div class="h-32 animate-pulse rounded-xl bg-stone-100 dark:bg-slate-900"></div>
                        <div class="h-32 animate-pulse rounded-xl bg-stone-100 dark:bg-slate-900"></div>
                    </div>
                    <div class="flex h-64 items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-stone-50 dark:border-slate-800 dark:bg-slate-900/50">
                        <div class="flex flex-col items-center">
                            <svg class="mb-3 h-8 w-8 animate-spin text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span class="text-sm font-medium text-slate-500">Syncing database activities...</span>
                        </div>
                    </div>
                </div>

                <!-- View Containers with Smooth Transitions -->
                <div x-data="{ viewFade: true }" x-init="$watch('currentView', () => { viewFade = false; setTimeout(() => viewFade = true, 50) })">
                    <div x-show="viewFade" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <!-- Table View -->
                        <div x-show="currentView === 'table' && !loading">
                            @include('spatie-activitylog-ui::components.table-view')
                        </div>

                        <!-- Timeline View -->
                        <div x-show="currentView === 'timeline' && !loading">
                            @include('spatie-activitylog-ui::components.timeline-view')
                        </div>

                        <!-- Analytics View -->
                        @if(config('spatie-activitylog-ui.features.analytics', true))
                            <div x-show="currentView === 'analytics' && !loading">
                                @include('spatie-activitylog-ui::components.analytics-dashboard')
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>

        <!-- Modals -->
        @include('spatie-activitylog-ui::components.activity-detail-modal')
        @if(config('spatie-activitylog-ui.features.exports', true))
            @include('spatie-activitylog-ui::components.export-modal')
        @endif
        @if(config('spatie-activitylog-ui.features.saved_views', true))
            @include('spatie-activitylog-ui::components.save-view-modal')
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function activityDashboard() {
            return {
                // State
                initialized: false,
                currentView: '{{ $view }}',
                loading: false,
                activities: [],
                totalActivities: 0,
                currentPage: 1,
                perPage: {{ config('spatie-activitylog-ui.ui.default_per_page', 25) }},
                totalPages: 1,
                showExportModal: false,
                @if(config('spatie-activitylog-ui.features.saved_views', true))
                    showSaveViewModal: false,
                @endif
            selectedActivity: null,
                currentFilters: { },

            init() {
                // Prevent multiple initializations
                if (this.initialized) return;
                this.initialized = true;

                // Initialize event listeners first
                this.filterChangedHandler = (event) => {
                    this.currentFilters = event.detail;
                    this.currentPage = 1; // Reset to first page
                    this.loadActivities();

                    // Also reload analytics if on analytics view
                    if (this.currentView === 'analytics') {
                        this.reloadAnalytics();
                    }
                };

                this.showExportModalHandler = () => {
                    this.showExportModal = true;
                };

                this.showActivityDetailHandler = (event) => {
                    this.selectedActivity = event.detail;
                };

                @if(config('spatie-activitylog-ui.features.saved_views', true))
                    this.showSaveViewModalHandler = (event) => {
                        this.showSaveViewModal = true;
                    };
                @endif

                // Remove any existing listeners first
                window.removeEventListener('filter-changed', this.filterChangedHandler);
                window.removeEventListener('show-export-modal', this.showExportModalHandler);
                window.removeEventListener('show-activity-detail', this.showActivityDetailHandler);
                @if(config('spatie-activitylog-ui.features.saved_views', true))
                    window.removeEventListener('show-save-view-modal', this.showSaveViewModalHandler);
                @endif
                window.removeEventListener('filter-panel-ready', this.filterChangedHandler);

                // Add event listeners
                window.addEventListener('filter-changed', this.filterChangedHandler);
                window.addEventListener('show-export-modal', this.showExportModalHandler);
                window.addEventListener('show-activity-detail', this.showActivityDetailHandler);
                @if(config('spatie-activitylog-ui.features.saved_views', true))
                    window.addEventListener('show-save-view-modal', this.showSaveViewModalHandler);
                @endif
                window.addEventListener('filter-panel-ready', this.filterChangedHandler);

                // Load initial data based on the default view
                if (this.currentView === 'analytics') {
                    this.reloadAnalytics();
                } else {
                    this.loadActivities();
                }
            },

                            async loadActivities(page = 1) {
                // Prevent multiple simultaneous calls
                if (this.loading) {
                    return;
                }

                this.loading = true;
                this.currentPage = page;

                try {
                    // Build query parameters
                    const params = new URLSearchParams();
                    params.append('page', page);
                    params.append('per_page', this.perPage);

                    // Add filters to params
                    Object.keys(this.currentFilters).forEach(key => {
                        const value = this.currentFilters[key];
                        if (value !== null && value !== undefined && value !== '') {
                            if (Array.isArray(value)) {
                                // Handle arrays properly (like event_types)
                                value.forEach(item => {
                                    params.append(`${key}[]`, item);
                                });
                            } else {
                                params.append(key, value);
                            }
                        }
                    });

                    // Make API call to get activities
                    const response = await fetch(`{{ route('spatie-activitylog-ui.api.activities.index') }}?${params}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json();

                    this.activities = result.data || [];
                    this.totalActivities = result.total || 0;
                    this.totalPages = result.last_page || 1;

                } catch (error) {
                    this.activities = [];
                    this.totalActivities = 0;

                    if (window.notify) {
                        window.notify.error('Error', 'Failed to load activities');
                    }
                } finally {
                    this.loading = false;
                }
            },

            changePage(page) {
                if (page >= 1 && page <= this.totalPages) {
                    this.loadActivities(page);
                }
            },

                            get hasActiveFilters() {
                return Object.keys(this.currentFilters).some(key => {
                    const value = this.currentFilters[key];
                    return value !== '' && value !== null &&
                        (Array.isArray(value) ? value.length > 0 : true) &&
                        !(key === 'date_preset' && value === 'all');
                });
            },

            exportActivities() {
                window.dispatchEvent(new CustomEvent('show-export-modal', {
                    detail: { filters: this.currentFilters }
                }));
            },

            showActivityDetail(activity) {
                window.dispatchEvent(new CustomEvent('show-activity-detail', {
                    detail: activity
                }));
            },

            getEventTypeColor(event) {
                const colors = {
                    'created': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                    'updated': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                    'deleted': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                    'restored': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                };
                return colors[event] || 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200';
            },

            getEventIcon(event) {
                const icons = {
                    'created': 'M12 6v6m0 0v6m0-6h6m-6 0H6',
                    'updated': 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                    'deleted': 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
                    'restored': 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'
                };
                return icons[event] || 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
            },

            getUserInitials(name) {
                if (!name) return '?';
                return name.split(' ').map(word => word[0]).join('').toUpperCase().slice(0, 2);
            },

            formatDate(dateString) {
                return new Date(dateString).toLocaleString();
            },

            truncateBatchUuid(batchUuid) {
                if (!batchUuid || batchUuid.length <= 16) {
                    return batchUuid || '';
                }

                return `${batchUuid.slice(0, 8)}...${batchUuid.slice(-4)}`;
            },

                            // Load more activities for timeline view
                            async loadMoreActivities() {
                if (this.currentView !== 'timeline' || this.loading || this.currentPage >= this.totalPages) {
                    return;
                }

                const nextPage = this.currentPage + 1;

                this.loading = true;

                try {
                    // Build query parameters
                    const params = new URLSearchParams();
                    params.append('page', nextPage);
                    params.append('per_page', this.perPage);

                    // Add filters to params
                    Object.keys(this.currentFilters).forEach(key => {
                        const value = this.currentFilters[key];
                        if (value !== null && value !== undefined && value !== '') {
                            if (Array.isArray(value)) {
                                value.forEach(item => {
                                    params.append(`${key}[]`, item);
                                });
                            } else {
                                params.append(key, value);
                            }
                        }
                    });

                    // Make API call to get activities
                    const response = await fetch(`{{ route('spatie-activitylog-ui.api.activities.index') }}?${params}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json();

                    // Append new activities to existing ones for timeline view
                    if (result.data && result.data.length > 0) {
                        this.activities = [...this.activities, ...result.data];
                        this.currentPage = nextPage;
                        this.totalPages = result.last_page || 1;
                    }

                } catch (error) {
                    console.error('Error loading more activities:', error);
                    if (window.notify) {
                        window.notify.error('Error', 'Failed to load more activities');
                    }
                } finally {
                    this.loading = false;
                }
            },

            // Smart view switching with context preservation
            switchView(view) {
                const previousView = this.currentView;
                this.currentView = view;

                // Smart view switching logic based on UX requirements
                if (view === 'timeline') {
                    // Timeline needs chronological context - reset to show from beginning
                    // This provides the full chronological story, which is essential for timeline UX
                    const wasOnLaterPage = this.currentPage > 1;
                    this.currentPage = 1;
                    this.activities = []; // Clear for fresh load

                    this.loadActivities();
                } else if (view === 'table') {
                    // Table view can handle any page - maintain current pagination
                    this.loadActivities();
                } else if (view === 'analytics') {
                    // Analytics doesn't use pagination
                    this.reloadAnalytics();
                }
            },

            // Reload analytics with current filters
            reloadAnalytics() {
                // Find the analytics component and reload it
                const analyticsComponent = document.querySelector('[x-data*="analyticsData"]');
                if (analyticsComponent && analyticsComponent._x_dataStack) {
                    const component = analyticsComponent._x_dataStack[0];
                    if (component && component.loadAnalytics) {
                        component.loadAnalytics(this.currentFilters);
                    }
                }
            }
        }
                    }
    </script>
@endpush
