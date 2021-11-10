<?php
namespace App\Action;

use App\Models\Population;

class ImportPopulationAction
{

    public function execute(): array
    {
        $sourceUlr = 'https://raw.githubusercontent.com/datasets/population/master/data/population.csv';

        $file = file_get_contents($sourceUlr);
        $data = explode("\n", $file);
        $count = 0;
        foreach ($data as $line) {
            $count++;
            if ($count == 1 || empty($line)) {
                continue; // skip header and last empty line
            }

            list($name, $code, $year, $pop) = str_getcsv($line);

            $population = new Population;
            $population->country = $name;
            $population->year = $year;
            $population->population = $pop;
            $population->save();

        }
        $response = [
            'imported' => $count - 2, // do not count header and empty line
        ];
        return $response;
    }
}
