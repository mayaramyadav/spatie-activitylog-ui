<?php

namespace Mayaram\SpatieActivitylogUi\Tests;

use Mayaram\SpatieActivitylogUi\Services\AnalyticsService;

class AnalyticsServiceTest extends TestCase
{
    public function test_it_caps_analytics_ranges_to_the_configured_limit(): void
    {
        config()->set('spatie-activitylog-ui.analytics.max_range_days', 90);

        [$startDate, $endDate] = $this->makeService()->exposeResolvedRange([
            'start_date' => '2020-01-01',
            'end_date' => '2026-01-01',
        ], 30);

        $this->assertSame('2025-10-03', $startDate->toDateString());
        $this->assertSame('2026-01-01', $endDate->toDateString());
    }

    public function test_it_normalizes_reversed_custom_ranges(): void
    {
        config()->set('spatie-activitylog-ui.analytics.max_range_days', 90);

        [$startDate, $endDate] = $this->makeService()->exposeResolvedRange([
            'start_date' => '2026-04-10',
            'end_date' => '2026-04-03',
        ], 30);

        $this->assertTrue($startDate->lessThanOrEqualTo($endDate));
        $this->assertSame('2026-04-03', $startDate->toDateString());
        $this->assertSame('2026-04-10', $endDate->toDateString());
    }

    public function test_it_ignores_invalid_dates_and_uses_the_default_window(): void
    {
        config()->set('spatie-activitylog-ui.analytics.max_range_days', 90);

        [$startDate, $endDate] = $this->makeService()->exposeResolvedRange([
            'start_date' => 'not-a-date',
            'end_date' => '',
        ], 6);

        $this->assertSame($endDate->copy()->subDays(6)->toDateString(), $startDate->toDateString());
    }

    public function test_it_uses_the_actual_all_time_range_when_requested(): void
    {
        [$startDate, $endDate] = $this->makeService([
            '2024-01-15 10:30:00',
            '2026-03-20 19:45:00',
        ])->exposeResolvedRange([
            'analytics_period' => 'all',
        ], 30);

        $this->assertSame('2024-01-15', $startDate->toDateString());
        $this->assertSame('2026-03-20', $endDate->toDateString());
    }

    private function makeService(?array $allTimeRange = null): AnalyticsService
    {
        return new class ($allTimeRange) extends AnalyticsService
        {
            public function __construct(private readonly ?array $allTimeRange = null)
            {
            }

            public function exposeResolvedRange(array $filters, int $defaultDays): array
            {
                return $this->resolveAnalyticsDateRange($filters, $defaultDays);
            }

            protected function getAllTimeRange(array $filters): ?array
            {
                if ($this->allTimeRange === null) {
                    return parent::getAllTimeRange($filters);
                }

                return [
                    $this->parseFilterDate($this->allTimeRange[0], false),
                    $this->parseFilterDate($this->allTimeRange[1], true),
                ];
            }
        };
    }
}
