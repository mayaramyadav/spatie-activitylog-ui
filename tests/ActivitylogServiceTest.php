<?php

namespace Mayaram\SpatieActivitylogUi\Tests;

use Illuminate\Support\Collection;
use Mayaram\SpatieActivitylogUi\Services\ActivitylogService;

class ActivitylogServiceTest extends TestCase
{
    public function test_it_normalizes_legacy_string_event_types(): void
    {
        $service = $this->makeService();

        $eventTypes = $service->exposeNormalizeEventTypes(collect([
            'created',
            ['value' => 'updated', 'label' => 'Updated'],
            ['value' => 'deleted'],
            null,
        ]));

        $this->assertSame([
            ['value' => 'created', 'label' => 'Created'],
            ['value' => 'updated', 'label' => 'Updated'],
            ['value' => 'deleted', 'label' => 'Deleted'],
        ], $eventTypes->toArray());
    }

    public function test_it_normalizes_legacy_string_styled_event_types(): void
    {
        $service = $this->makeService();

        $eventTypes = $service->exposeNormalizeStyledEventTypes(collect([
            'created',
            ['value' => 'updated', 'label' => 'Updated'],
        ]));

        $this->assertSame('created', $eventTypes[0]['value']);
        $this->assertSame('Created', $eventTypes[0]['label']);
        $this->assertIsArray($eventTypes[0]['colors']);
        $this->assertSame('updated', $eventTypes[1]['value']);
        $this->assertSame('Updated', $eventTypes[1]['label']);
        $this->assertIsArray($eventTypes[1]['colors']);
    }

    private function makeService(): ActivitylogService
    {
        return new class extends ActivitylogService
        {
            public function exposeNormalizeEventTypes(Collection $eventTypes): Collection
            {
                return $this->normalizeEventTypes($eventTypes);
            }

            public function exposeNormalizeStyledEventTypes(Collection $eventTypes): Collection
            {
                return $this->normalizeStyledEventTypes($eventTypes);
            }
        };
    }
}
