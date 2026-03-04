<?php

namespace App\Console\Commands;

use App\Actions\ImportPostcodesAction;
use Exception;
use Illuminate\Console\Command;

class ImportPostcodes extends Command
{
    protected $signature = 'import:postcodes {file?}';

    protected $description = 'Import UK postcodes from a CSV file';

    public function handle(ImportPostcodesAction $importPostcodesAction): int
    {
        $file = $this->argument('file') ?? database_path('seeders/resources/uk-towns.csv');

        $this->info('Importing postcodes...');

        try {
            $count = $importPostcodesAction->execute($file, function ($currentCount) {
                $this->info("Imported {$currentCount} records...");
            });
        } catch (Exception $e) {
            $this->error($e->getMessage());

            return 1;
        }

        $this->info("Import completed! Total: {$count} postcodes imported.");

        return 0;
    }
}
