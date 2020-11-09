<?php

namespace App\Helpers\Elasticsearch;

use App\Models\Product;
use Elasticsearch\ClientBuilder;
use Illuminate\Database\Eloquent\Collection;

class ElasticSearch
{
    /**
     * Array of hosts
     *
     * @var array
     */
    private $hosts;

    /**
     * ElasticSearch client
     *
     * @var \Elasticsearch\Client
     */
    private $builder;

    /**
     * ElasticSearch query
     *
     * @var object
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/full-text-queries.html
     */
    private $query;

    /**
     * ElasticSearch suggestions
     *
     * @var object
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-suggesters.html
     */
    private $suggest;

    /**
     * ElasticSearch highlighting
     *
     * @var object
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/7.9/highlighting.html
     */
    private $highlight;

    /**
     * Size of results in query
     *
     * @var int
     */
    private $limit;

    public function __construct(array $hosts)
    {
        $this->hosts = $hosts;
        $this->builder = ClientBuilder::create()->setHosts($this->hosts)->build();
        $this->query = (object)[];
        $this->suggest = (object)[];
        $this->highlight = (object)[];
    }

    /**
     * Return information of all existing indices
     *
     * @return array
     */
    public function getAllIndices(): array
    {
        return $this->builder->cat()->indices();
    }

    /**
     * Return information of particular index
     *
     * @param string $indexName
     * @return array
     */
    public function getIndex(string $indexName)
    {
        return $this->builder->indices()->get(['index' => $indexName]);
    }

    /**
     * Check if particular index exist
     *
     * @param string $indexName
     * @return bool
     */
    public function indexExist(string $indexName): bool
    {
        return $this->builder->indices()->exists(['index' => $indexName]);
    }

    /**
     * Create an index
     *
     * @param string $indexName
     * @param array $params
     * @return array
     */
    public function createIndex(string $indexName, array $params)
    {
        return $this->builder->indices()->create([
            'index' => $indexName,
            'body' => $params
        ]);
    }

    /**
     * Return index mappings
     *
     * @param array $indices
     * @return array
     */
    public function getIndexMappings(array $indices)
    {
        return $this->builder->indices()->getMapping(['index' => $indices]);
    }

    /**
     * Return index settings
     *
     * @param array $indices
     * @return array
     */
    public function getIndexSettings(array $indices)
    {
        return $this->builder->indices()->getSettings(['index' => $indices]);
    }

    /**
     * Update index settings
     *
     * @param string $indexName
     * @param array $settings
     * @return array
     */
    public function updateIndexSettings(string $indexName, array $settings)
    {
        return $this->builder->indices()->putSettings([
            'index' => $indexName,
            'body' => [
                'settings' => $settings
            ]
        ]);
    }

    /**
     * Update index mappings
     *
     * @param string $indexName
     * @param array $properties
     * @return array
     */
    public function updateIndexMappings(string $indexName, array $properties)
    {
        return $this->builder->indices()->putMapping([
            'index' => $indexName,
            'body' => [
                'properties' => $properties
            ]
        ]);
    }

    /**
     * Add documents collection to index
     *
     * @param string $indexName
     * @param Collection $documents
     * @return bool
     */
    public function addDocumentsToIndex(string $indexName, Collection $documents)
    {
        foreach ($documents as $document) {
            $this->builder->index([
                'index' => $indexName,
                'id' => $document->id,
                'body' => $document->toArray()
            ]);
        }

        return true;
    }

    /**
     * Add document to index
     *
     * @param string $indexName
     * @param Product $document
     * @return array
     */
    public function addDocumentToIndex(string $indexName, Product $document)
    {
        return $this->builder->index([
            'index' => $indexName,
            'id' => $document->id,
            'body' => $document->toArray()
        ]);
    }

    /**
     * Update an existing document in index
     *
     * @param string $indexName
     * @param Product $document
     * @return array
     */
    public function updateDocumentInIndex(string $indexName, Product $document)
    {
        return $this->builder->update([
            'index' => $indexName,
            'id' => $document->id,
            'body' => [
                'doc' => $document->toArray()
            ]
        ]);
    }

    /**
     * Delete document from particular index
     *
     * @param string $indexName
     * @param Product $document
     * @return array
     */
    public function deleteDocumentFromIndex(string $indexName, Product $document)
    {
        return $this->builder->delete([
            'index' => $indexName,
            'id' => $document->id
        ]);
    }

    /**
     * Return number of documents in index
     *
     * @param string $indexName
     * @return int
     */
    public function countIndexDocuments(string $indexName): int
    {
        return collect($this->builder->count(['index' => $indexName]))->get('count');
    }

    /**
     * Set query body
     *
     * @param array $body
     * @return $this
     */
    public function query(array $body): self
    {
        $this->query = $body;

        return $this;
    }

    /**
     * Set query suggestions
     *
     * @param array $body
     * @return $this
     */
    public function suggest(array $body): self
    {
        $this->suggest = $body;

        return $this;
    }

    /**
     * Set query suggestions
     *
     * @param array $body
     * @return $this
     */
    public function highlight(array $body): self
    {
        $this->highlight = $body;

        return $this;
    }

    /**
     * Set query limit (size)
     *
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit = 25): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Search for documents in index
     *
     * @param string $indexName
     * @return array
     */
    public function search(string $indexName)
    {
        return $this->builder->search([
            'index' => $indexName,
            'body' => [
                'size' => $this->limit,
                'query' => $this->query,
                'suggest' => $this->suggest,
                'highlight' => $this->highlight
            ]
        ]);
    }

    /**
     * Delete a particular index
     *
     * @param string $indexName
     * @return array
     */
    public function deleteIndex(string $indexName)
    {
        return $this->builder->indices()->delete(['index' => $indexName]);
    }
}