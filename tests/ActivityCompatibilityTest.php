<?php

namespace Mayaram\SpatieActivitylogUi\Tests;

use Illuminate\Support\Collection;
use Mayaram\SpatieActivitylogUi\Models\Activity;

class ActivityCompatibilityTest extends TestCase
{
    public function test_it_normalizes_standard_spatie_change_payloads(): void
    {
        $activity = new Activity();
        $activity->setAttribute('properties', new Collection([
            'attributes' => ['name' => 'New name'],
            'old' => ['name' => 'Old name'],
        ]));

        $this->assertSame([
            'old' => ['name' => 'Old name'],
            'attributes' => ['name' => 'New name'],
        ], $activity->attribute_changes);

        $this->assertTrue($activity->hasPropertyChanges());
        $this->assertSame('Changed name', $activity->getChangesSummary());
    }

    public function test_it_normalizes_legacy_nested_attribute_changes_payloads(): void
    {
        $activity = new Activity();
        $activity->setAttribute('properties', [
            'attribute_changes' => [
                'attributes' => ['status' => 'published'],
                'old' => ['status' => 'draft'],
            ],
        ]);

        $this->assertSame([
            'old' => ['status' => 'draft'],
            'attributes' => ['status' => 'published'],
        ], $activity->attribute_changes);
    }

    public function test_it_appends_attribute_changes_when_serialized(): void
    {
        $activity = new Activity();
        $activity->setAttribute('properties', [
            'attributes' => ['email' => 'new@example.com'],
            'old' => ['email' => 'old@example.com'],
        ]);

        $this->assertArrayHasKey('attribute_changes', $activity->toArray());
    }
}
