<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Department;
use App\Models\Property;
use App\Models\Role;
use App\Services\TenantBackupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use ZipArchive;

class BackupExportLogicTest extends TestCase
{
    use RefreshDatabase;

    protected $propertyA;

    protected $propertyB;

    protected function setUp(): void
    {
        parent::setUp();

        // Create two isolated properties
        $this->propertyA = Property::factory()->create(['name' => 'Property A', 'code' => 'P-A']);
        $this->propertyB = Property::factory()->create(['name' => 'Property B', 'code' => 'P-B']);

        // Seed data for Property A
        $roleA = Role::factory()->create(['property_id' => $this->propertyA->id, 'name' => 'Role A']);
        $deptA = Department::factory()->create(['property_id' => $this->propertyA->id, 'name' => 'Dept A']);
        $catA = Category::factory()->create(['property_id' => $this->propertyA->id, 'name' => 'Cat A']);
        $assetA = Asset::factory()->create([
            'property_id' => $this->propertyA->id,
            'department_id' => $deptA->id,
            'category_id' => $catA->id,
            'name' => 'Asset A',
        ]);

        // Seed data for Property B
        $roleB = Role::factory()->create(['property_id' => $this->propertyB->id, 'name' => 'Role B']);
        $deptB = Department::factory()->create(['property_id' => $this->propertyB->id, 'name' => 'Dept B']);
        $catB = Category::factory()->create(['property_id' => $this->propertyB->id, 'name' => 'Cat B']);
        $assetB = Asset::factory()->create([
            'property_id' => $this->propertyB->id,
            'department_id' => $deptB->id,
            'category_id' => $catB->id,
            'name' => 'Asset B',
        ]);

        // Fake the public storage for media bundling tests
        Storage::fake('public');
    }

    public function test_backup_service_only_exports_active_property_data()
    {
        $service = new TenantBackupService($this->propertyA);
        $zipPath = $service->build();

        $this->assertFileExists($zipPath);

        // Open the generated zip
        $zip = new ZipArchive;
        $this->assertTrue($zip->open($zipPath) === true, 'Failed to open the generated zip file');

        // Extract data.json
        $dataJsonString = $zip->getFromName('data.json');
        $this->assertNotFalse($dataJsonString, 'data.json is missing from the archive');

        $data = json_decode($dataJsonString, true);

        // Assert Property A is exported
        $this->assertEquals('Property A', $data['property']['name']);

        // Assert related models only belong to Property A
        $this->assertCount(1, $data['roles']);
        $this->assertEquals('Role A', $data['roles'][0]['name']);

        $this->assertCount(1, $data['departments']);
        $this->assertEquals('Dept A', $data['departments'][0]['name']);

        $this->assertCount(1, $data['categories']);
        $this->assertEquals('Cat A', $data['categories'][0]['name']);

        $this->assertCount(1, $data['assets']);
        $this->assertEquals('Asset A', $data['assets'][0]['name']);

        $zip->close();
        unlink($zipPath);
    }

    public function test_exported_data_is_stripped_of_property_id()
    {
        $service = new TenantBackupService($this->propertyA);
        $zipPath = $service->build();

        $zip = new ZipArchive;
        $zip->open($zipPath);
        $data = json_decode($zip->getFromName('data.json'), true);
        $zip->close();
        unlink($zipPath);

        // Verify property_id does not exist anywhere in the payload
        $this->assertArrayNotHasKey('property_id', $data['property']);
        $this->assertArrayNotHasKey('property_id', $data['roles'][0]);
        $this->assertArrayNotHasKey('property_id', $data['departments'][0]);
        $this->assertArrayNotHasKey('property_id', $data['categories'][0]);
        $this->assertArrayNotHasKey('property_id', $data['assets'][0]);

        // Extra check: local numeric IDs should be stripped from standard models
        $this->assertArrayNotHasKey('id', $data['property']);
        $this->assertArrayNotHasKey('id', $data['roles'][0]);
        $this->assertArrayNotHasKey('id', $data['departments'][0]);
        $this->assertArrayNotHasKey('id', $data['categories'][0]);
        $this->assertArrayNotHasKey('id', $data['assets'][0]);
    }

    public function test_media_bundling_packages_branding_and_attachments()
    {
        // Give Property A some media
        $this->propertyA->update([
            'logo_path' => 'branding/logo.png',
            'background_image_path' => 'branding/bg.jpg',
        ]);

        // Put fake files in the fake public storage
        Storage::disk('public')->put('branding/logo.png', 'fake-logo-content');
        Storage::disk('public')->put('branding/bg.jpg', 'fake-bg-content');

        // Add an attachment for Asset A
        $assetA = Asset::where('name', 'Asset A')->first();
        $assetA->attachments()->create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'path' => 'attachments/doc.pdf',
            'original_name' => 'doc.pdf',
        ]);
        Storage::disk('public')->put('attachments/doc.pdf', 'fake-doc-content');

        $service = new TenantBackupService($this->propertyA);
        $zipPath = $service->build();

        $zip = new ZipArchive;
        $zip->open($zipPath);

        // Verify files are physically packed into the zip inside the media/ prefix
        $this->assertNotFalse($zip->locateName('media/branding/logo.png'), 'Logo missing from zip');
        $this->assertNotFalse($zip->locateName('media/branding/bg.jpg'), 'Background missing from zip');
        $this->assertNotFalse($zip->locateName('media/attachments/doc.pdf'), 'Attachment missing from zip');

        $this->assertEquals('fake-logo-content', $zip->getFromName('media/branding/logo.png'));

        $zip->close();
        unlink($zipPath);
    }
}
