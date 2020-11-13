<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class SearchController extends Controller
{
    /**
     * Search for products in ElasticSearch
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $search = elasticsearch()
            ->query([
                'multi_match' => [
                    'query' => request()->input('term') ?? '',
                    'fields' => ['name^8', 'description^5', 'categories.name^5', 'price^7'],
                    'analyzer' => 'product_analyzer',
                    'type' => 'phrase_prefix',
                    'tie_breaker' => 0.3
                ],
            ])
            ->suggest([
                'product_suggest' => [
                    'text' => request()->input('term') ?? '',
                    'term' => [
                        'field' => 'name',
                        'size' => 1,
                        'analyzer' => 'simple',
                        'sort' => 'score',
                        'suggest_mode' => 'always',
                        'min_doc_freq' => 1
                    ]
                ]
            ])
            ->highlight([
                'pre_tags' => '<span style="color:red;">',
                'post_tags' => '</span>',
                'fields' => [
                    'name' => (object)[]
                ]
            ])
            ->limit(5)
            ->search('products');

        return response()->json($search, Response::HTTP_OK);
    }
}