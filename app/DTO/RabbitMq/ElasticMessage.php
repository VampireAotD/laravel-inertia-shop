<?php

namespace App\DTO\RabbitMq;

use App\DTO\DataTransferObject;

class ElasticMessage extends DataTransferObject
{
    protected string $indexName;

    protected int $documentId;

    public function __construct(int $documentId, string $indexName = 'products')
    {
        $this->setIndexName($indexName);
        $this->setDocumentId($documentId);
    }

    /**
     * @return string
     */
    public function getIndexName(): string
    {
        return $this->indexName;
    }

    /**
     * @param string $indexName
     * @return ElasticMessage
     */
    public function setIndexName(string $indexName): ElasticMessage
    {
        $this->indexName = $indexName;
        return $this;
    }

    /**
     * @return int
     */
    public function getDocumentId(): int
    {
        return $this->documentId;
    }

    /**
     * @param int $documentId
     * @return ElasticMessage
     */
    public function setDocumentId(int $documentId): ElasticMessage
    {
        $this->documentId = $documentId;
        return $this;
    }
}
