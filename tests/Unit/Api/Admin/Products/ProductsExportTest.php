<?php

namespace Tests\Unit\Api\Admin\Products;

use App\Exports\ProductsExport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Export;
use App\Models\Product;
use App\Enums\ExportStatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class ProductsExportTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Brand::factory()->count(2)->create();
        Category::factory()->count(2)->create();
        Product::factory()->count(5)->create();
    }

    public function testStoreExportInStorage(): void
    {
        Storage::fake();
        $fileName = 'test.xlsx';

        /** @var Export $export */
        $export = Export::query()->create([
            'user_id' => 1,
            'type' => 'products',
            'status' => 'pending',
            'filename' => $fileName
        ]);

        $max = Product::query()->count();

        Excel::store(new ProductsExport(1, $max, $export), $fileName);

        Storage::disk()->assertExists('test.xlsx');

        $spreadsheet = IOFactory::load(Storage::disk()->path('test.xlsx'));
        $worksheet = $spreadsheet->getActiveSheet();
        $headers = $worksheet->toArray()[0];

        $this->assertEquals([
            'SKU',
            'Name',
            'Description',
            'Price',
            'Stock',
            'Status',
            'Category',
            'Brand',
        ], $headers);
    }

    public function testExportFailure(): void
    {
        $fileName = 'test.xlsx';

        /** @var Export $export */
        $export = Export::query()->create([
            'user_id' => 1,
            'type' => 'products',
            'status' => ExportStatusEnum::PENDING->value,
            'filename' => $fileName
        ]);

        (new ProductsExport(1, 1, $export))->failed(new \Exception('Test exception'));

        $this->assertEquals(ExportStatusEnum::FAILED->value, $export->refresh()->status);
        $this->assertEquals('Test exception', $export->error);
    }
}
