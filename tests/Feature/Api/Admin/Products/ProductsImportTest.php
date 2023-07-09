<?php

namespace Tests\Feature\Api\Admin\Products;

use App\Enums\ImportStatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Tests\Feature\Utilities\UserTestCase;

class ProductsImportTest extends UserTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->adminUser);
        Excel::fake();
    }

    public function testImportXlsxFileWithProducts(): void
    {
        $file = UploadedFile::fake()->create('test_1.xlsx');

        $response = $this->postJson(route('admin.api.products.import'), [
            'file' => $file,
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['status']);
        $response->assertJson(['status' => 'import queued']);
        $this->assertDatabaseHas('imports', [
            'filename' => 'test_1.xlsx',
            'status' => ImportStatusEnum::PENDING,
        ]);
    }

    public function testImportSupportedFiles(): void
    {
        Excel::fake();

        $xlsxFile = UploadedFile::fake()->create('test_1.xlsx');

        $response = $this->postJson(route('admin.api.products.import'), [
            'file' => $xlsxFile,
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['status']);
        $response->assertJson(['status' => 'import queued']);

        $csvFile = UploadedFile::fake()->create('test_1.csv');

        $response = $this->postJson(route('admin.api.products.import'), [
            'file' => $csvFile,
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['status']);
        $response->assertJson(['status' => 'import queued']);

        $xlsFile = UploadedFile::fake()->create('test_1.xls');

        $response = $this->postJson(route('admin.api.products.import'), [
            'file' => $xlsFile,
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['status']);
        $response->assertJson(['status' => 'import queued']);

        $odsFile = UploadedFile::fake()->create('test_1.ods');

        $response = $this->postJson(route('admin.api.products.import'), [
            'file' => $odsFile,
        ]);

        $response->assertUnprocessable();
    }

    public function testCheckImportStatus(): void
    {
        $file = UploadedFile::fake()->create('test_1.xlsx');

        $response = $this->postJson(route('admin.api.products.import'), [
            'file' => $file,
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['status']);
        $response->assertJson(['status' => 'import queued']);

        $response = $this->getJson(route('admin.api.products.import.check', ['fileName' => 'test_1.xlsx']));

        $response->assertOk();
        $response->assertJsonStructure(['status']);
        $response->assertJson(['status' => ImportStatusEnum::PENDING->value]);
    }
}
