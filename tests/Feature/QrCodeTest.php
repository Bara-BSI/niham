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
        $user = User::factory()->create();
        $asset = Asset::factory()->create([
            'department_id' => $user->department_id, // Ensure user can view asset if needed
        ]);

        $response = $this->actingAs($user)
            ->get(route('assets.qr', $asset));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }
}
