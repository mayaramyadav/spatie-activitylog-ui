<?php

namespace Mayaram\SpatieActivitylogUi\Tests;

class RoutesTest extends TestCase
{
    public function test_dashboard_route_is_registered(): void
    {
        $route = app('router')->getRoutes()->getByName('spatie-activitylog-ui.dashboard');

        $this->assertNotNull($route);
        $this->assertSame('spatie-activitylog-ui', $route->uri());
        $this->assertContains('web', $route->gatherMiddleware());
    }

    public function test_api_routes_are_registered(): void
    {
        $expectedRoutes = [
            'spatie-activitylog-ui.api.activities.index',
            'spatie-activitylog-ui.api.activities.show',
            'spatie-activitylog-ui.api.activities.related',
            'spatie-activitylog-ui.api.search.suggestions',
            'spatie-activitylog-ui.api.filter.options',
            'spatie-activitylog-ui.api.event-types.styling',
            'spatie-activitylog-ui.api.activities.recent',
            'spatie-activitylog-ui.api.analytics',
            'spatie-activitylog-ui.api.analytics.heatmap',
            'spatie-activitylog-ui.api.users.profile',
            'spatie-activitylog-ui.api.views.index',
            'spatie-activitylog-ui.api.views.save',
            'spatie-activitylog-ui.api.views.delete',
            'spatie-activitylog-ui.api.export',
            'spatie-activitylog-ui.api.export.formats',
            'spatie-activitylog-ui.api.export.progress',
            'spatie-activitylog-ui.api.export.cleanup',
            'spatie-activitylog-ui.export.download',
        ];

        foreach ($expectedRoutes as $routeName) {
            $this->assertNotNull(app('router')->getRoutes()->getByName($routeName), $routeName);
        }
    }
}
