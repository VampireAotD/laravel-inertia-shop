<?php

use App\Helpers\Elasticsearch\ElasticSearch;

if (!function_exists('elasticsearch')) {
    function elasticsearch(array $hosts = ['localhost:9200'])
    {
        return new ElasticSearch($hosts);
    }
}