<?php
namespace App\Action;

use App\Models\Population;
use App\QueryHelper\PopulationQueryHelper;
use Illuminate\Http\Request;

class SearchPopulationAction
{

    public function execute(Request $request):array
    {
        $helper = new PopulationQueryHelper(Population::query());

        $helper->addTextSearchNE('country', $request->country);
        $helper->addIntervalSearchNE('year', $request->yearFrom, $request->yearTo);
        $helper->addIntervalSearchNE('population', $request->populationFrom, $request->populationTo);

        $count = $helper->getResultCount();
        $maxPages = $helper->getMaxPages($count);

        $helper->paginate($searchVars['page'] ?? 1);
        $helper->selectColumns(['country', 'year', 'population']);
        $result = $helper->getResult();

        $response = [
            'pagination' => [
                'match' => $count,
                'maxPages' => $maxPages,
            ],
            'result' =>  $result,
        ];

        return $response;
    }
}
