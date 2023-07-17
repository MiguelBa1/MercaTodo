<?php

namespace Tests\Feature\Api\Admin\Products;

use App\Enums\ExportStatusEnum;
use App\Exports\ProductsExport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Export;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Tests\Feature\Utilities\UserTestCase;

class ProductsExportTest extends UserTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->adminUser);
        Brand::factory()->count(1)->create();
        Category::factory()->count(1)->create();
        Product::factory()->count(10)->create();
    }

    public function testExportProducts(): void
    {
        Excel::fake();

        $response = $this->getJson(route('api.admin.products.export', [
            'from' => Product::all()->min('id'),
            'to' => Product::all()->max('id'),
        ]));

        $response->assertOk();
        $response->assertJsonStructure(['filename']);
        $this->assertNotNull($response->json('filename'));
        $this->assertDatabaseHas('exports', [
            'filename' => $response->json('filename'),
            'status' => ExportStatusEnum::PENDING,
        ]);

        Excel::assertQueued('exports/' . $response->json('filename'), function (ProductsExport $export) {
            return $export->query()->count() === Product::all()->count();
        });
    }

    public function testCheckExportStatus(): void
    {
        $fileName = 'products-' . date('Y-m-d-H-i-s') . '.xlsx';

        Export::factory()->create([
            'filename' => $fileName,
            'status' => ExportStatusEnum::READY,
        ]);

        $response = $this->getJson(route('api.admin.products.export.check', [
            'fileName' => $fileName,
        ]));

        $response->assertOk();
        $response->assertJson(['status' => ExportStatusEnum::READY->value]);
    }

    public function testDownloadExport(): void
    {
        $fileName = 'products-' . date('Y-m-d-H-i-s') . '.xlsx';

        Storage::fake();
        Storage::put('exports/' . $fileName, 'test');

        $response = $this->get(route('api.admin.products.export.download', [
            'fileName' => $fileName,
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertHeader('content-disposition', 'attachment; filename=' . $fileName);

        $downloadedContent = $response->streamedContent();
        $expectedContent = Storage::get('exports/' . $fileName);
        $this->assertEquals($expectedContent, $downloadedContent);
    }

    public function testCheckExportWhenExportRecordDoesNotExist(): void
    {
        $response = $this->getJson(route('api.admin.products.export.check', [
            'fileName' => 'non-existing-file.xlsx',
        ]));

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
        $response->assertJson([
            'message' => __('validation.custom.export.record_not_found_error'),
        ]);
    }

    public function testDownloadExportWhenExportDoesNotExist(): void
    {
        Export::factory()->create([
            'filename' => 'existing-file.xlsx',
            'status' => ExportStatusEnum::READY,
        ]);

        $response = $this->get(route('api.admin.products.export.download', [
            'fileName' => 'existing-file.xlsx',
        ]));

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
        $response->assertJson([
            'message' => __('validation.custom.export.file_not_found_error'),
        ]);
    }

    public function testCheckExportWhenExportFailed(): void
    {
        $fileName = 'products-' . date('Y-m-d-H-i-s') . '.xlsx';

        Export::factory()->create([
            'filename' => $fileName,
            'status' => ExportStatusEnum::FAILED,
        ]);

        $response = $this->getJson(route('api.admin.products.export.check', [
            'fileName' => $fileName,
        ]));

        $response->assertServerError();
        $response->assertJsonStructure(['message']);
        $response->assertJson([
            'message' => __('validation.custom.export.export_failed_error'),
        ]);
    }
}
