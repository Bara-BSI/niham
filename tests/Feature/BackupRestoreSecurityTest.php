<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackupRestoreSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $property;

    protected function setUp(): void
    {
        parent::setUp();
        $this->property = Property::factory()->create();
    }

    public function test_guests_are_redirected_to_login()
    {
        $this->post(route('backup.download'))->assertRedirect(route('login'));
        $this->post(route('backup.restore'))->assertRedirect(route('login'));
    }

    public function test_normal_users_receive_403_when_attempting_backup_operations()
    {
        $role = Role::factory()->create(['name' => 'user', 'property_id' => $this->property->id]);
        $user = User::factory()->create([
            'property_id' => $this->property->id,
            'role_id' => $role->id,
            'is_super_admin' => false,
        ]);

        $this->actingAs($user)
            ->post(route('backup.download'))
            ->assertForbidden();

        $this->actingAs($user)
            ->post(route('backup.restore'))
            ->assertForbidden();
    }

    public function test_admins_can_access_backup_operations()
    {
        $role = Role::factory()->create(['name' => 'admin', 'property_id' => $this->property->id]);
        $admin = User::factory()->create([
            'property_id' => $this->property->id,
            'role_id' => $role->id,
            'is_super_admin' => false,
        ]);

        // Download returns the zip file directly (200), but if no data, it might still build a zip.
        $response = $this->actingAs($admin)->post(route('backup.download'));
        $response->assertSuccessful();

        // Restore will fail validation without file, but it's 302 not 403!
        $restoreResponse = $this->actingAs($admin)->post(route('backup.restore'));
        $restoreResponse->assertRedirect()->assertSessionHasErrors('backup');
    }

    public function test_superadmins_without_active_property_session_are_redirected_back_with_warning()
    {
        $superadmin = User::factory()->create(['is_super_admin' => true]);

        $this->actingAs($superadmin)
            ->withSession(['active_property_id' => null])
            ->post(route('backup.download'))
            ->assertRedirect(route('assets.index'))
            ->assertSessionHas('warning', __('messages.backup_select_property_warning'));

        $this->actingAs($superadmin)
            ->withSession(['active_property_id' => null])
            ->post(route('backup.restore'))
            ->assertRedirect(route('assets.index'))
            ->assertSessionHas('warning', __('messages.backup_select_property_warning'));
    }

    public function test_superadmins_with_active_property_session_can_access_operations()
    {
        $superadmin = User::factory()->create(['is_super_admin' => true]);

        $this->actingAs($superadmin)
            ->withSession(['active_property_id' => $this->property->id])
            ->post(route('backup.download'))
            ->assertSuccessful();

        $this->actingAs($superadmin)
            ->withSession(['active_property_id' => $this->property->id])
            ->post(route('backup.restore'))
            ->assertRedirect()->assertSessionHasErrors('backup');
    }
}
