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
    public function __invoke()
    {
        $term = elasticsearch()->analyzeString('products', [
            'text' => request()->input('term') ?? '',
            'analyzer' => 'russian_replace_analyzer'
        ]);

        $analyzed_term = $term['tokens'][0]['token'];

        $search = elasticsearch()
            ->query([
                'multi_match' => [
                    'query' => $analyzed_term,
                    'fields' => ['name^8', 'description^5', 'categories.name^5', 'price^5'],
                    'analyzer' => 'product_analyzer',
                    'fuzziness' => 'AUTO',
                ],
            ])
            ->suggest([
                'product_suggest' => [
                    'text' => $analyzed_term,
                    'term' => [
                        'field' => 'name',
                        'size' => 1,
                        'analyzer' => 'product_analyzer',
                        'sort' => 'score',
                        'suggest_mode' => 'always',
                    ]
                ]
            ])
            ->highlight([
                'pre_tags' => '<span style="color : red">',
                'post_tags' => '</span>',
                'fields' => [
                    'name' => (object)[]
                ]
            ])
            ->search('products');

        return response()->json($search, Response::HTTP_OK);
    }
}
