<?php

namespace Mayaram\SpatieActivitylogUi\Tests;

class DashboardViewTest extends TestCase
{
    public function test_dashboard_view_renders_without_blade_section_errors(): void
    {
        $view = view('spatie-activitylog-ui::pages.dashboard', [
            'data' => collect(),
            'filters' => [],
            'view' => 'table',
            'filterOptions' => [
                'causers' => [],
                'subject_types' => [],
                'event_types' => [],
                'date_presets' => [],
            ],
            'savedViews' => [],
            'perPage' => 25,
        ])->render();

        $this->assertStringContainsString('Activity Log', $view);
    }
}
