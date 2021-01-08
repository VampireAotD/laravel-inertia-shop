<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\Replacements;
use App\Models\Product;
use Illuminate\Console\Command;

class InitializeElasticSearch extends Command
{
    use Replacements;

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
     * Array for replaced letters and symbols
     *
     * @var string[]
     */
    protected $replacementArray;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->replacementArray = $this->getRussianReplacementArray();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $start = microtime(true);

        $bar = $this->output->createProgressBar();

        if (elasticsearch()->indexExist('products')) {
            $this->line($bar->advance() . ' Deleting index....');

            elasticsearch()->deleteIndex('products');
        }

        $this->line($bar->advance() . ' Creating index....');

        elasticsearch()->createIndex('products', [
            'settings' => [
                'index' => [
                    'max_ngram_diff' => 50,
                ],

                'analysis' => [
                    'char_filter' => [
                        'russian_replace' => [
                            'type' => 'mapping',
                            'mappings' => $this->replacementArray
                        ]
                    ],

                    'filter' => [
                        'stop_russian_words' => [
                            'type' => 'stop',
                            'ignore_case' => true,
                            'stopwords' => [
                                'найти',
                                'купить',
                                'приобрести'
                            ]
                        ],

                        'stop_english_words' => [
                            'type' => 'stop',
                            'stopwords' => '_english_'
                        ],

                        'custom_english_stop_words' => [
                            'type' => 'stop',
                            'ignore_case' => true,
                            'stopwords' => [
                                'buy',
                                'find',
                                'get'
                            ]
                        ],

                        'hunspell_ru' => [
                            'type' => 'hunspell',
                            'locale' => 'ru_RU'
                        ],

                        'hunspell_en_us' => [
                            'type' => 'hunspell',
                            'locale' => 'en_US'
                        ],

                        'custom_nGram' => [
                            'type' => 'nGram',
                            'min_gram' => 3,
                            'max_gram' => 8
                        ],

                        'english_stemmer' => [
                            'type' => 'stemmer',
                            'language' => 'english'
                        ],

                        'russian_stemmer' => [
                            'type' => 'stemmer',
                            'language' => 'russian'
                        ],
                    ],

                    'analyzer' => [
                        'product_analyzer' => [
                            'type' => 'custom',

                            'char_filter' => [
                                'html_strip',
                                'russian_replace'
                            ],

                            'tokenizer' => 'whitespace',

                            'filter' => [
                                'lowercase',
                                'stop_russian_words',
                                'stop_english_words',
                                'custom_english_stop_words'
                            ]
                        ],

                        'hunspell_search_analyzer' => [
                            'type' => 'custom',

                            'tokenizer' => 'whitespace',

                            'filter' => [
                                'hunspell_ru',
                                'hunspell_en_us',
                            ]
                        ],

                        'russian_replace_analyzer' => [
                            'type' => 'custom',

                            'tokenizer' => 'keyword',

                            'char_filter' => 'russian_replace',

                            'filter' => [
                                'hunspell_ru',
                                'hunspell_en_us',
                            ]
                        ]
                    ]
                ],
            ],

            'mappings' => [
                'properties' => [
                    'name' => [
                        'type' => 'text',
                        'analyzer' => 'product_analyzer'
                    ],

                    'description' => [
                        'type' => 'text',
                        'analyzer' => 'product_analyzer'
                    ],

                    'slug' => [
                        'type' => 'keyword',
                    ],

                    'price' => [
                        'type' => 'text',
                    ],

                    'categories.name' => [
                        'type' => 'text',
                        'analyzer' => 'product_analyzer'
                    ],
                ]
            ]
        ]);

        $this->line($bar->advance() . ' Adding documents to index....');

        Product::with('categories')->get()->chunk(1000)->map(function ($collection) {
            elasticsearch()->addDocumentsToIndex('products', $collection);
        });

        $this->line($bar->finish() . ' Index was created in ' . (microtime(true) - $start) . ' seconds!');

        return true;
    }
}
