<?php

namespace App\DTO\RabbitMq;

use App\DTO\DataTransferObject;

class UserEmailMessage extends DataTransferObject
{
    /**
     * User email
     *
     * @var string
     */
    private string $email;

    /**
     * Products that user ordered
     *
     * @var array
     */
    private array $productKeys;

    public function __construct($email, $productKeys)
    {
        $this->setEmail($email);
        $this->setProductKeys($productKeys);
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return UserEmailMessage
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return array
     */
    public function getProductKeys(): array
    {
        return $this->productKeys;
    }

    /**
     * @param array $productKeys
     * @return UserEmailMessage
     */
    public function setProductKeys(array $productKeys): self
    {
        $this->productKeys = $productKeys;
        return $this;
    }
}
