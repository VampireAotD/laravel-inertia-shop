<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class InitializeElasticSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize some of main indices for this project in ElasticSearch';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (elasticsearch()->indexExist('products')) {
            echo 'Deleting index....' . $this->newLine();

            elasticsearch()->deleteIndex('products');
        }

        echo 'Creating index....' . $this->newLine();

        elasticsearch()->createIndex('products', [
            'settings' => [
                'index' => [
                    'max_ngram_diff' => 50
                ],
                'analysis' => [
                    'filter' => [
                        'stop_words_russian' => [
                            'type' => 'stop',
                            'stopwords' => ['найти', 'купить', 'приобрести']
                        ],
                        'stop_words_english' => [
                            'type' => 'stop',
                            'stopwords' => '_english_'
                        ],
                        'custom_nGram' => [
                            'type' => 'nGram',
                            'min_gram' => 3,
                            'max_gram' => 8
                        ]
                    ],
                    'analyzer' => [
                        'product_analyzer' => [
                            'type' => 'custom',
                            'char_filter' => 'html_strip',
                            'tokenizer' => 'whitespace',
                            'filter' => ['lowercase', 'stop_words_russian', 'stop_words_english', 'custom_nGram']
                        ]
                    ]
                ]
            ],
            'mappings' => [
                'properties' => [
                    'name' => ['type' => 'text', 'analyzer' => 'product_analyzer'],
                    'description' => ['type' => 'text', 'analyzer' => 'product_analyzer'],
                    'slug' => ['type' => 'text', 'analyzer' => 'product_analyzer'],
                    'categories.name' => ['type' => 'text', 'analyzer' => 'product_analyzer'],
                ]
            ]
        ]);

        echo 'Adding documents to index....' . $this->newLine();

        elasticsearch()->addDocumentsToIndex('products', Product::with('categories')->get());

        echo 'Index was created!' . $this->newLine();

        return true;
    }
}
