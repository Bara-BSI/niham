<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QrCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_qr_code_can_be_generated()
    {
        $property = \App\Models\Property::factory()->create();
        $user = User::factory()->create(['property_id' => $property->id]);
        $user->role->update(['perm_assets' => 'view only']);

        $this->actingAs($user);

        $asset = Asset::factory()->create([
            'property_id' => $property->id,
            'department_id' => $user->department_id, // Ensure user can view asset if needed
        ]);

        $response = $this->get(route('assets.qr', $asset));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }
}
