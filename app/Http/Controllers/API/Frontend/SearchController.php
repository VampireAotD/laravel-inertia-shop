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
                    'fields' => ['name^7', 'description^5', 'categories.name^5', 'price^7'],
                    'analyzer' => 'product_analyzer',
                    'fuzziness' => 'AUTO',
                    'prefix_length' => 0,
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