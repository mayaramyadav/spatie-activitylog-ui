<?php

namespace Mayaram\SpatieActivitylogUi\Tests;

use Illuminate\Support\Facades\Gate;

class GateTest extends TestCase
{
    public function test_gate_allows_any_authenticated_user_by_default(): void
    {
        $user = new class {
            public string $email = 'user@example.com';
        };

        $this->assertTrue(Gate::forUser($user)->allows('viewActivityLogUi'));
    }

    public function test_gate_denies_users_outside_allowed_user_list(): void
    {
        config()->set('spatie-activitylog-ui.access.allowed_users', ['admin@example.com']);

        $user = new class {
            public string $email = 'user@example.com';
        };

        $this->assertFalse(Gate::forUser($user)->allows('viewActivityLogUi'));
    }

    public function test_gate_allows_users_present_in_allowed_user_list(): void
    {
        config()->set('spatie-activitylog-ui.access.allowed_users', ['admin@example.com']);

        $user = new class {
            public string $email = 'admin@example.com';
        };

        $this->assertTrue(Gate::forUser($user)->allows('viewActivityLogUi'));
    }

    public function test_gate_allows_users_with_matching_role(): void
    {
        config()->set('spatie-activitylog-ui.access.allowed_users', []);
        config()->set('spatie-activitylog-ui.access.allowed_roles', ['admin']);

        $user = new class {
            public string $email = 'user@example.com';

            public function hasAnyRole(array $roles): bool
            {
                return in_array('admin', $roles, true);
            }
        };

        $this->assertTrue(Gate::forUser($user)->allows('viewActivityLogUi'));
    }
}
