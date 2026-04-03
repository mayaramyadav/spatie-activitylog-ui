<?php

namespace Mayaram\SpatieActivitylogUi\Tests;

use Illuminate\Support\Collection;
use Mayaram\SpatieActivitylogUi\Models\Activity;

class ActivityCompatibilityTest extends TestCase
{
    public function test_it_normalizes_standard_spatie_change_payloads(): void
    {
        $activity = new Activity();
        $activity->setAttribute('attribute_changes', new Collection([
            'attributes' => ['name' => 'New name'],
            'old' => ['name' => 'Old name'],
        ]));

        $this->assertSame([
            'attributes' => ['name' => 'New name'],
            'old' => ['name' => 'Old name'],
        ], $activity->attribute_changes->toArray());

        $this->assertTrue($activity->hasPropertyChanges());
        $this->assertSame('Changed name', $activity->getChangesSummary());
    }

    public function test_it_prefers_v5_attribute_changes_over_properties(): void
    {
        $activity = new Activity();
        $activity->setAttribute('attribute_changes', [
            'attributes' => ['status' => 'approved'],
            'old' => ['status' => 'pending'],
        ]);

        $this->assertSame([
            'attributes' => ['status' => 'approved'],
            'old' => ['status' => 'pending'],
        ], $activity->attribute_changes->toArray());
    }

    public function test_it_appends_attribute_changes_when_serialized(): void
    {
        $activity = new Activity();
        $activity->setAttribute('attribute_changes', [
            'attributes' => ['email' => 'new@example.com'],
            'old' => ['email' => 'old@example.com'],
        ]);

        $this->assertArrayHasKey('attribute_changes', $activity->toArray());
    }
}
