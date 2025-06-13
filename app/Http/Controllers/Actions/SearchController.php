<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Actions\SearchRequest;
use App\Repositories\SearchRepository;

class SearchController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(
        SearchRequest $request,
        SearchRepository $repository,
    )
    {
        $request->validated();

        return response()->json([
            'searchResult' => $repository->search($request->searchTerm),
        ]);
    }
}
