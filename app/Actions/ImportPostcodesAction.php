<?php

namespace App\Actions;

use App\Models\Postcode;
use Exception;
use Illuminate\Support\Facades\File;

class ImportPostcodesAction
{
    /**
     * Import UK postcodes from a CSV file.
     *
     * @return int Total number of imported postcodes.
     *
     * @throws Exception
     */
    public function execute(?string $file = null, ?callable $progressCallback = null): int
    {
        if (! File::exists($file)) {
            throw new Exception("File not found: {$file}");
        }

        $handle = fopen($file, 'r');
        $header = fgetcsv($handle);

        $chunkSize = 1000;
        $batch = [];
        $count = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            $latitude = (float) ($data['latitude']);
            $longitude = (float) ($data['longitude']);

            $batch[] = [
                'place_name' => $data['location'],
                'county' => $data['county'],
                'country' => $data['country'],
                'postcode' => $data['postcode'],
                'type' => $data['type'],
                'latitude' => $latitude,
                'longitude' => $longitude,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= $chunkSize) {
                Postcode::insert($batch);
                $count += count($batch);
                $batch = [];

                if ($progressCallback) {
                    $progressCallback($count);
                }
            }
        }

        if (count($batch) > 0) {
            Postcode::insert($batch);
            $count += count($batch);

            if ($progressCallback) {
                $progressCallback($count);
            }
        }

        fclose($handle);

        return $count;
    }
}
