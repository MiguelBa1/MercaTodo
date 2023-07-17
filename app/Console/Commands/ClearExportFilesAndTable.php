<?php

namespace App\Console\Commands;

use App\Models\Export;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearExportFilesAndTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-export-files-and-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all export files and reset the exports table.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $files = Storage::files('exports');

        foreach ($files as $file) {
            Storage::delete($file);
            $this->info('Deleted file: ' . $file);
        }

        Export::query()->truncate();
        $this->info('Truncated exports table.');

        return 0;
    }
}
