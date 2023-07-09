<?php

namespace Tests\Unit\Api\Admin\Products;

use App\Enums\ImportStatusEnum;
use App\Imports\ProductsImport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Import;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Tests\Feature\Utilities\UserTestCase;

class ProductsImportTest extends UserTestCase
{
    use RefreshDatabase;

    private Import $validImport;
    private Import $invalidImport;
    private Import $emptyImport;

    private UploadedFile $validFile;

    private UploadedFile $invalidFile;

    private UploadedFile $emptyFile;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->adminUser);

        $filePath = base_path('tests/fixtures/Products/valid_data.xlsx');
        $this->validFile = new UploadedFile(
            $filePath,
            'valid_data.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $filePath = base_path('tests/fixtures/Products/invalid_data.xlsx');
        $this->invalidFile = new UploadedFile(
            $filePath,
            'invalid_data.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $filePath = base_path('tests/fixtures/Products/empty_rows.xlsx');
        $this->emptyFile = new UploadedFile(
            $filePath,
            'empty_rows.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $this->validImport = Import::factory()->create([
            'filename' => $this->validFile->getClientOriginalName(),
            'user_id' => $this->adminUser->id,
        ]);

        $this->invalidImport = Import::factory()->create([
            'filename' => $this->invalidFile->getClientOriginalName(),
            'user_id' => $this->adminUser->id,
        ]);

        $this->emptyImport = Import::factory()->create([
            'filename' => $this->emptyFile->getClientOriginalName(),
            'user_id' => $this->adminUser->id,
        ]);
    }

    public function testImportProductsQueue(): void
    {
        Excel::fake();

        Excel::queueImport(new ProductsImport($this->validImport), $this->validImport->filename);

        Excel::assertQueued($this->validImport->filename);
    }

    public function testImportValidFile(): void
    {
        Excel::import(new ProductsImport($this->validImport), $this->validFile);

        $this->assertDatabaseHas('imports', [
            'filename' => $this->validFile->getClientOriginalName(),
            'status' => ImportStatusEnum::READY->value,
        ]);

        $this->assertDatabaseHas('products', [
            'sku' => '12345',
            'name' => 'Product 1',
            'description' => 'This is a product 1',
            'price' => 100.55,
            'stock' => 10,
            'status' => 1,
            'category_id' => Category::query()->where('name', 'Cat 1')->first()->id,
            'brand_id' => Brand::query()->where('name', 'Brand 1')->first()->id,
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Cat 1',
        ]);

        $this->assertDatabaseCount('categories', 1);

        $this->assertDatabaseHas('brands', [
            'name' => 'Brand 1',
        ]);

        $this->assertDatabaseCount('brands', 1);
    }

    public function testImportInvalidFile(): void
    {
        Excel::import(new ProductsImport($this->invalidImport), $this->invalidFile);

        $expectedErrors = [
            "2" => [
                "The sku field must be a number.",
                "The name field must be at least 3 characters.",
                "The price field must not be greater than 100000.",
                "The stock field must not be greater than 1000.",
                "The brand field must be at least 2 characters.",
                "The category field must be at least 2 characters."
            ]
        ];

        $this->assertDatabaseHas('imports', [
            'filename' => $this->invalidFile->getClientOriginalName(),
            'status' => ImportStatusEnum::HAS_ERRORS->value,
        ]);

        $this->invalidImport->refresh();
        $errorsInImport = $this->invalidImport->errors;
        $this->assertEquals($expectedErrors, $errorsInImport);
    }

    public function testImportEmptyRowsDoesNotInsertProduct(): void
    {
        Excel::import(new ProductsImport($this->emptyImport), $this->emptyFile);

        $this->assertDatabaseHas('imports', [
            'filename' => $this->emptyFile->getClientOriginalName(),
            'status' => ImportStatusEnum::READY->value,
        ]);

        $this->assertDatabaseCount('products', 0);

        $this->emptyImport->refresh();
        $errorsInImport = $this->emptyImport->errors;
        $this->assertEquals([], $errorsInImport);
    }

    public function testValidImportUpdatesProductInDatabase(): void
    {
        $category = Category::factory()->create([
            'name' => 'Cat 1',
        ]);
        $brand = Brand::factory()->create([
            'name' => 'Brand 1',
        ]);

        Product::factory()->create([
            'sku' => '12345',
            'name' => 'Product 1',
            'description' => 'Product to be updated',
            'price' => 1,
            'stock' => 1,
            'status' => 0,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]);

        Excel::import(new ProductsImport($this->validImport), $this->validFile);

        $this->assertDatabaseHas('imports', [
            'filename' => $this->validFile->getClientOriginalName(),
            'status' => ImportStatusEnum::READY->value,
        ]);

        $this->assertDatabaseHas('products', [
            'sku' => '12345',
            'name' => 'Product 1',
            'description' => 'This is a product 1',
            'price' => 100.55,
            'stock' => 10,
            'status' => 1,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]);
    }
}
