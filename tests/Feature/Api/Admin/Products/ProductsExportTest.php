<?php

namespace Tests\Feature\Api\Admin\Products;

use App\Exports\ProductsExport;
use App\Models\Brand;
use App\Models\Category;
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

        $response = $this->getJson(route('admin.api.products.export', [
            'from' => 1,
            'to' => 10,
        ]));

        $response->assertOk();
        $response->assertJsonStructure(['filename']);
        $this->assertNotNull($response->json('filename'));

        Excel::assertQueued('exports/' . $response->json('filename'), function (ProductsExport $export) {
            return $export->query()->count() === 10;
        });
    }

    public function testCheckExportStatus(): void
    {
        $fileName = 'products-' . date('Y-m-d-H-i-s') . '.xlsx';

        Storage::fake();
        Storage::put('exports/' . $fileName, 'test');

        $response = $this->getJson(route('admin.api.products.export.check', [
            'fileName' => $fileName,
        ]));

        $response->assertOk();
        $response->assertJson(['status' => 'ready']);
    }

    public function testDownloadExport(): void
    {
        $fileName = 'products-' . date('Y-m-d-H-i-s') . '.xlsx';

        Storage::fake();
        Storage::put('exports/' . $fileName, 'test');

        $response = $this->get(route('admin.api.products.export.download', [
            'fileName' => $fileName,
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertHeader('content-disposition', 'attachment; filename=' . $fileName);

        $downloadedContent = $response->streamedContent();
        $expectedContent = Storage::get('exports/' . $fileName);
        $this->assertEquals($expectedContent, $downloadedContent);
    }
}
