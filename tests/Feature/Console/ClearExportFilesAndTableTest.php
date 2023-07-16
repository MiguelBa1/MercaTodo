<?php

namespace Tests\Feature\Console;

use App\Console\Commands\ClearExportFilesAndTable;
use App\Enums\ExportStatusEnum;
use App\Models\Export;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ClearExportFilesAndTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_files_and_truncates_table(): void
    {
        Storage::fake();
        Storage::put('exports/test1.txt', 'Hello');
        Storage::put('exports/test2.txt', 'Hello');

        Export::query()->create(['filename' => 'test1.txt', 'status' => ExportStatusEnum::PENDING, 'type' => 'products']);
        Export::query()->create(['filename' => 'test2.txt', 'status' => ExportStatusEnum::PENDING, 'type' => 'products']);

        Artisan::call(ClearExportFilesAndTable::class);

        $this->assertFalse(Storage::exists('exports/test1.txt'));
        $this->assertFalse(Storage::exists('exports/test2.txt'));

        $this->assertEquals(0, Export::query()->count());
    }
}
