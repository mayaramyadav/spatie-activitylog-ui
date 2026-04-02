<?php

namespace Mayaram\SpatieActivitylogUi\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AnalyticsEndpointTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        Schema::create('activity_log', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->nullableMorphs('subject');
            $table->nullableMorphs('causer');
            $table->string('event')->nullable();
            $table->uuid('batch_uuid')->nullable();
            $table->json('properties')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('activity_log');

        parent::tearDown();
    }

    public function test_analytics_endpoint_returns_successful_payload(): void
    {
        now()->setTestNow(now()->startOfDay()->addHours(10));

        foreach ([
            ['description' => 'created', 'event' => 'created', 'subject_type' => 'App\\Models\\Vehicle', 'subject_id' => 71],
            ['description' => 'updated', 'event' => 'updated', 'subject_type' => 'App\\Models\\Vehicle', 'subject_id' => 72],
        ] as $activity) {
            \DB::table('activity_log')->insert([
                'log_name' => 'default',
                'description' => $activity['description'],
                'subject_type' => $activity['subject_type'],
                'subject_id' => $activity['subject_id'],
                'causer_type' => null,
                'causer_id' => null,
                'event' => $activity['event'],
                'batch_uuid' => (string) Str::uuid(),
                'properties' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $response = $this->getJson('/spatie-activitylog-ui/api/analytics?period=today');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.total_activities', 2)
            ->assertJsonPath('data.activities_today', 2);
    }
}
