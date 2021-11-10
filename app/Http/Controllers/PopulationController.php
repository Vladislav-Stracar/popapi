<?php

namespace App\Http\Controllers;

use App\Action\ImportPopulationAction;
use App\Action\SearchPopulationAction;
use App\Http\Requests\SearchPopulationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PopulationController extends Controller
{

    /**
     * Import data form dataset
     * @return JsonResponse
     */
    public function loadData(ImportPopulationAction $action): JsonResponse
    {
        $response = $action->execute();

        return response()->json($response, 200);
    }

    /**
     * Do search on population data
     * @param Request $request
     * @return JsonResponse
     */
    public function search(SearchPopulationRequest $request, SearchPopulationAction $action): JsonResponse
    {
        $response = $action->execute($request);

        return response()->json($response, 200);
    }

}
