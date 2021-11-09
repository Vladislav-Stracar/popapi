<?php

namespace App\Http\Controllers;

use App\Models\Population;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\QueryHelper\PopulationQueryHelper;

class PopulationController extends Controller
{

    /**
     * Import data form dataset
     * @return JsonResponse
     */
    public function loadData(): JsonResponse
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
        return response()->json($response, 200);
    }

    /**
     * Do search on population data
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $searchVars = $request->all();
        // do validation of input
        $validator = $this->getValidationFactory()->make($searchVars, [
            'country' => 'string',
            'yearFrom' => 'numeric|required_with:yearTo',
            'yearTo' => 'numeric|required_with:yearFrom',
            'populationFrom' => 'numeric|required_with:populationTo',
            'populationTo' => 'numeric|required_with:populationFrom',
            'page' => 'numeric|gt:0',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // start building query
        $helper = new PopulationQueryHelper(Population::query());

        if (!empty($searchVars['country'])) {
            $helper->addCountrySearch($searchVars['country']);
        }
        if (!empty($searchVars['yearFrom']) && !empty($searchVars['yearTo'])) {
            $helper->addYearSearch($searchVars['yearFrom'], $searchVars['yearTo']);
        }
        if (!empty($searchVars['populationFrom']) && !empty($searchVars['populationTo'])) {
            $helper->addPopulationSearch($searchVars['populationFrom'], $searchVars['populationTo']);
        }

        $query = $helper->getQuery();
        $count = $query->count();
        $maxPages = $helper->getMaxPages($count);

        $helper->paginate($searchVars['page'] ?? 1);
        $query->select(['country', 'year', 'population']);
        $result = $query->get();

        $response = [
            'pagination' => [
                'match' => $count,
                'maxPages' => $maxPages,
            ],
            'result' =>  $result,
        ];
        return response()->json($response, 200);
    }

}
