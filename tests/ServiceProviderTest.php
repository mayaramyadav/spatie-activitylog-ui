<?php

namespace Mayaram\SpatieActivitylogUi\Tests;

use Mayaram\SpatieActivitylogUi\Services\ActivitylogService;
use Mayaram\SpatieActivitylogUi\Services\AnalyticsService;
use Mayaram\SpatieActivitylogUi\Services\ExportService;

class ServiceProviderTest extends TestCase
{
    public function test_package_configuration_is_merged(): void
    {
        $this->assertSame('spatie-activitylog-ui', config('spatie-activitylog-ui.route.prefix'));
        $this->assertSame('Activity Log', config('spatie-activitylog-ui.ui.title'));
    }

    public function test_package_services_are_registered_as_singletons(): void
    {
        $this->assertTrue($this->app->bound(ActivitylogService::class));
        $this->assertTrue($this->app->bound(AnalyticsService::class));
        $this->assertTrue($this->app->bound(ExportService::class));

        $this->assertSame(
            $this->app->make(ActivitylogService::class),
            $this->app->make(ActivitylogService::class)
        );
        $this->assertSame(
            $this->app->make(AnalyticsService::class),
            $this->app->make(AnalyticsService::class)
        );
        $this->assertSame(
            $this->app->make(ExportService::class),
            $this->app->make(ExportService::class)
        );
    }
}
