<!-- Analytics Dashboard Component -->
<div x-data="analyticsData()"
     x-init="init()"
     class="space-y-6">

    <!-- Analytics Header -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-soft p-6 border border-zinc-200 dark:border-zinc-800 transition-all duration-300">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <h3 class="text-xl font-bold text-zinc-900 dark:text-white tracking-tight">Intelligence & Insights</h3>
                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-500 font-medium whitespace-nowrap overflow-hidden text-ellipsis">Monitoring activity patterns and system engagement metrics</p>
            </div>

            <!-- Time Period Selector -->
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <div class="inline-flex p-1 bg-zinc-100 dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700/50 shadow-inner-soft shrink-0">
                    <template x-for="period in ['today', '7', '30', '90']" :key="period">
                        <button @click="selectedPeriod = period; loadAnalytics()"
                                :class="selectedPeriod === period 
                                    ? 'bg-white dark:bg-zinc-900 text-indigo-600 dark:text-indigo-400 shadow-soft font-black' 
                                    : 'text-zinc-500 dark:text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300 font-bold'"
                                class="px-4 py-1.5 text-[10px] uppercase tracking-widest rounded-lg transition-all duration-200"
                                x-text="period === 'today' ? 'Today' : period + 'D'"></button>
                    </template>
                    <button @click="selectedPeriod = 'custom'"
                            :class="selectedPeriod === 'custom' 
                                ? 'bg-white dark:bg-zinc-900 text-indigo-600 dark:text-indigo-400 shadow-soft font-black' 
                                : 'text-zinc-500 dark:text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300 font-bold'"
                            class="px-4 py-1.5 text-[10px] uppercase tracking-widest rounded-lg transition-all duration-200">
                        Custom
                    </button>
                </div>

                <!-- Custom Range Inputs -->
                <div x-show="selectedPeriod === 'custom'" x-collapse class="flex items-center gap-2">
                    <div class="relative">
                        <input type="date" x-model="customStartDate" @change="loadAnalytics()"
                               class="block px-3 py-1.5 text-xs bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg focus:ring-2 focus:ring-indigo-500/20 text-zinc-900 dark:text-white">
                    </div>
                    <div class="relative">
                        <input type="date" x-model="customEndDate" @change="loadAnalytics()"
                               class="block px-3 py-1.5 text-xs bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg focus:ring-2 focus:ring-indigo-500/20 text-zinc-900 dark:text-white">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <!-- Stats Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Card -->
        <div class="relative bg-white dark:bg-zinc-900/50 p-6 rounded-2xl border border-zinc-100 dark:border-zinc-800 shadow-soft overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500/5 dark:bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/10 transition-colors"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 flex items-center justify-center bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-xl shadow-inner-soft">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Total Events</p>
                    <p class="text-2xl font-black text-zinc-900 dark:text-white tabular-nums" x-text="stats.total || '0'"></p>
                </div>
            </div>
        </div>

        <!-- Today Card -->
        <div class="bg-white dark:bg-zinc-900/50 p-6 rounded-2xl border border-zinc-100 dark:border-zinc-800 shadow-soft relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/5 dark:bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/10 transition-colors"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 flex items-center justify-center bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl shadow-inner-soft">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Recorded Today</p>
                    <p class="text-2xl font-black text-zinc-900 dark:text-white tabular-nums" x-text="stats.today || '0'"></p>
                </div>
            </div>
        </div>

        <!-- Week Card -->
        <div class="bg-white dark:bg-zinc-900/50 p-6 rounded-2xl border border-zinc-100 dark:border-zinc-800 shadow-soft relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-500/5 dark:bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/10 transition-colors"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 flex items-center justify-center bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl shadow-inner-soft">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Active This Week</p>
                    <p class="text-2xl font-black text-zinc-900 dark:text-white tabular-nums" x-text="stats.activities_this_week || '0'"></p>
                </div>
            </div>
        </div>

        <!-- Month Card -->
        <div class="bg-white dark:bg-zinc-900/50 p-6 rounded-2xl border border-zinc-100 dark:border-zinc-800 shadow-soft relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-500/5 dark:bg-orange-500/10 rounded-full blur-2xl group-hover:bg-orange-500/10 transition-colors"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 flex items-center justify-center bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-xl shadow-inner-soft">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Monthly Traffic</p>
                    <p class="text-2xl font-black text-zinc-900 dark:text-white tabular-nums" x-text="stats.activities_this_month || '0'"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Event Concentration -->
        <div class="bg-white dark:bg-zinc-900 shadow-soft rounded-2xl border border-zinc-100 dark:border-zinc-800 p-6">
            <div class="flex items-center justify-between mb-8">
                <h4 class="text-sm font-black text-zinc-900 dark:text-white uppercase tracking-widest">Event Concentration</h4>
                <div class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse shadow-soft"></div>
            </div>
            
            <div class="space-y-6">
                <template x-for="type in eventTypes" :key="type.name">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="text-[10px] font-black text-zinc-900 dark:text-white uppercase tracking-wider" x-text="type.name"></span>
                                <span class="ml-2 px-1.5 py-0.5 bg-zinc-50 dark:bg-zinc-800 text-zinc-400 dark:text-zinc-600 text-[9px] font-bold rounded" x-text="type.count"></span>
                            </div>
                            <span class="text-[10px] font-black text-zinc-400 dark:text-zinc-500" x-text="type.percentage + '%'"></span>
                        </div>
                        <div class="h-2 w-full bg-zinc-50 dark:bg-zinc-800/50 rounded-full overflow-hidden border border-zinc-100 dark:border-zinc-800/50 shadow-inner-soft">
                            <div class="h-full rounded-full transition-all duration-1000 ease-out"
                                 :class="`bg-${window.ActivityTypeStyler?.getColor(type.name) || 'zinc'}-500 shadow-[0_0_8px_rgba(var(--tw-shadow-color),0.4)]`"
                                 :style="`width: ${type.percentage}%`"
                                 style="--tw-shadow-color: inherit;"></div>
                        </div>
                    </div>
                </template>

                <div x-show="!loading && eventTypes.length === 0" class="text-center py-10 opacity-30">
                    <p class="text-xs font-bold uppercase tracking-widest text-zinc-400">Telemetry Silenced</p>
                </div>
            </div>
        </div>

        <!-- Top Propulsion -->
        <div class="bg-white dark:bg-zinc-900 shadow-soft rounded-2xl border border-zinc-100 dark:border-zinc-800 p-6">
            <h4 class="text-sm font-black text-zinc-900 dark:text-white uppercase tracking-widest mb-8">Active Initiators</h4>
            <div class="space-y-4">
                <template x-for="user in topUsers" :key="user.id">
                    <div class="flex items-center justify-between p-3 bg-zinc-50/50 dark:bg-zinc-800/30 rounded-xl border border-zinc-100 dark:border-zinc-800 hover:border-zinc-200 dark:hover:border-zinc-700 transition-all duration-300">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white dark:bg-zinc-900 flex items-center justify-center border border-zinc-200 dark:border-zinc-800 shadow-inner-soft">
                                <span class="text-xs font-black text-zinc-400" x-text="user.name?.charAt(0) || '?'"></span>
                            </div>
                            <div>
                                <p class="text-xs font-black text-zinc-900 dark:text-white" x-text="user.name"></p>
                                <p class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500" x-text="user.email"></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-black text-zinc-900 dark:text-white tabular-nums" x-text="user.activity_count"></p>
                            <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-tighter">Actions</p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Resource Heatmap -->
        <div class="bg-white dark:bg-zinc-900 shadow-soft rounded-2xl border border-zinc-100 dark:border-zinc-800 p-6 lg:col-span-2">
            <h4 class="text-sm font-black text-zinc-900 dark:text-white uppercase tracking-widest mb-8">High-Traffic Resources</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="model in popularModels" :key="model.type">
                    <div class="p-4 bg-zinc-50/50 dark:bg-zinc-800/30 rounded-2xl border border-zinc-100 dark:border-zinc-800 group hover:bg-zinc-100 dark:hover:bg-zinc-800/50 transition-all">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-12 h-12 rounded-2xl bg-white dark:bg-zinc-900 flex items-center justify-center border border-zinc-200 dark:border-zinc-800 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-zinc-300 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <div class="text-right">
                                <span class="text-xl font-black text-zinc-900 dark:text-white tabular-nums" x-text="model.activity_count"></span>
                                <p class="text-[9px] font-black text-zinc-400 uppercase tracking-tighter">Interactions</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-black text-zinc-900 dark:text-white truncate" x-text="model.name"></p>
                            <p class="text-[10px] font-bold text-zinc-400 dark:text-zinc-600 truncate font-mono" x-text="model.type.split('\\').pop()"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Activity Propulsion Chart -->
        <div class="bg-white dark:bg-zinc-900 shadow-soft rounded-2xl border border-zinc-100 dark:border-zinc-800 p-6 lg:col-span-2 overflow-hidden">
            <h4 class="text-sm font-black text-zinc-900 dark:text-white uppercase tracking-widest mb-8">Activity Magnitude Over Time</h4>
            <div class="h-80 w-full relative">
                <canvas id="activityTrendsChart"></canvas>
            </div>
        </div>

        <!-- Granular Feed Progression -->
        <div class="bg-white dark:bg-zinc-900 shadow-soft rounded-2xl border border-zinc-100 dark:border-zinc-800 p-6 lg:col-span-2">
            <h4 class="text-sm font-black text-zinc-900 dark:text-white uppercase tracking-widest mb-8">Distribution Progression</h4>
            <div class="space-y-4">
                <template x-for="day in timeline" :key="day.date">
                    <div class="flex items-center gap-6">
                        <div class="w-32 shrink-0">
                            <span class="text-[11px] font-black text-zinc-900 dark:text-white tabular-nums" x-text="day.date"></span>
                            <span class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest" x-text="day.day_name"></span>
                        </div>
                        <div class="flex-1">
                            <div class="h-4 w-full bg-zinc-50 dark:bg-zinc-800/50 rounded-lg group relative overflow-hidden border border-zinc-100 dark:border-zinc-800/50">
                                <div class="h-full bg-indigo-500/20 dark:bg-indigo-500/30 rounded-lg transition-all duration-1000"
                                     :style="`width: ${day.percentage}%`"
                                     :class="{ 'opacity-20': day.count === 0 }"></div>
                                <div class="absolute inset-y-0 left-0 flex items-center px-3">
                                    <span class="text-[9px] font-black text-indigo-600 dark:text-indigo-400 tabular-nums shadow-soft" x-text="day.count + ' EVENTS'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Loading Sentinel -->
    <div x-show="loading" x-transition x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-900/10 dark:bg-white/5 backdrop-blur-sm">
        <div class="bg-white dark:bg-zinc-900 p-8 rounded-3xl shadow-soft border border-zinc-200 dark:border-zinc-800 flex flex-col items-center gap-4">
            <div class="w-12 h-12 border-4 border-indigo-500/20 border-t-indigo-500 rounded-full animate-spin"></div>
            <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] animate-pulse">Syncing Telemetry</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('analyticsData', () => ({
        stats: {},
        eventTypes: [],
        topUsers: [],
        timeline: [],
        popularModels: [],
        activityTrends: {},
        loading: true,
        selectedPeriod: 'today',
        customStartDate: '',
        customEndDate: '',
        chart: null,

        init() {
            this.loadAnalytics();
        },

        async loadAnalytics() {
            try {
                this.loading = true;
                let url = '{{ route("spatie-activitylog-ui.api.analytics") }}';
                let params = new URLSearchParams();

                if (this.selectedPeriod === 'custom') {
                    if (this.customStartDate) params.append('start_date', this.customStartDate);
                    if (this.customEndDate) params.append('end_date', this.customEndDate);
                } else if (this.selectedPeriod === 'today') {
                    params.append('period', 'today');
                } else {
                    params.append('period', this.selectedPeriod);
                }

                const response = await fetch(`${url}?${params.toString()}`, {
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

                const data = await response.json();

                if (data.success) {
                    this.stats = {
                        total: data.data.total_activities,
                        today: data.data.activities_today,
                        activities_this_week: data.data.activities_this_week,
                        activities_this_month: data.data.activities_this_month
                    };

                    this.eventTypes = data.data.event_types;
                    this.topUsers = data.data.top_users;
                    this.timeline = data.data.timeline;
                    this.popularModels = data.data.popular_models;
                    this.activityTrends = data.data.activity_trends;

                    if (this.activityTrends && document.getElementById('activityTrendsChart')) {
                        this.initActivityTrendsChart();
                    }
                }
            } catch (error) {
                console.error('Error loading analytics:', error);
                if (window.notify) {
                    window.notify.error('Error', 'Failed to load analytics data');
                }
            } finally {
                this.loading = false;
            }
        },

        initActivityTrendsChart() {
            const canvas = document.getElementById('activityTrendsChart');
            if (!canvas) return;

            if (this.chart instanceof Chart) {
                this.chart.destroy();
            }

            const ctx = canvas.getContext('2d');
            const isDark = document.documentElement.classList.contains('dark');
            const primaryColor = isDark ? '#818cf8' : '#4f46e5';
            const gridColor = isDark ? 'rgba(39, 39, 42, 0.5)' : 'rgba(228, 228, 231, 0.5)';
            const textColor = isDark ? '#71717a' : '#a1a1aa';

            this.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: this.activityTrends.dates,
                    datasets: this.activityTrends.datasets.map((dataset, idx) => {
                        const colors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
                        const color = colors[idx % colors.length];
                        
                        return {
                            label: dataset.label.toUpperCase(),
                            data: dataset.data.map(d => d.count),
                            borderColor: color,
                            backgroundColor: (context) => {
                                const chart = context.chart;
                                const {ctx, chartArea} = chart;
                                if (!chartArea) return null;
                                const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                                gradient.addColorStop(0, `${color}00`);
                                gradient.addColorStop(1, `${color}20`);
                                return gradient;
                            },
                            borderWidth: 3,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: color,
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 3,
                            tension: 0.4,
                            fill: true,
                        };
                    })
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            align: 'end',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 20,
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 10,
                                    weight: '900'
                                },
                                color: textColor,
                            }
                        },
                        tooltip: {
                            backgroundColor: isDark ? '#18181b' : '#fff',
                            titleColor: isDark ? '#fff' : '#18181b',
                            bodyColor: isDark ? '#d4d4d8' : '#71717a',
                            borderColor: isDark ? '#27272a' : '#e4e4e7',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: true,
                            usePointStyle: true,
                            titleFont: {
                                family: "'Inter', sans-serif",
                                size: 11,
                                weight: '900'
                            },
                            bodyFont: {
                                family: "'Inter', sans-serif",
                                size: 10,
                                weight: '500'
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 9,
                                    weight: '700'
                                },
                                color: textColor,
                                padding: 10
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: gridColor,
                                drawBorder: false,
                            },
                            ticks: {
                                stepSize: 10,
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 9,
                                    weight: '700'
                                },
                                color: textColor,
                                padding: 10
                            }
                        }
                    }
                }
            });
        },

        destroy() {
            if (this.chart instanceof Chart) {
                this.chart.destroy();
                this.chart = null;
            }
        }
    }));
});
</script>
