<?php

namespace App\DTO\RabbitMq;

use App\DTO\DataTransferObject;

class AdminEmailMessageDto extends DataTransferObject
{
    /**
     * @var string
     */
    private string $to;

    /**
     * @var string
     */
    private string $user;

    /**
     * @var array
     */
    private array $products;

    public function __construct($to, $user, $products)
    {
        $this->setTo($to);
        $this->setUser($user);
        $this->setProducts($products);
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     * @return AdminEmailMessageDto
     */
    public function setTo(string $to): self
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $user
     * @return AdminEmailMessageDto
     */
    public function setUser(string $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param array $products
     * @return AdminEmailMessageDto
     */
    public function setProducts(array $products): AdminEmailMessageDto
    {
        $this->products = $products;
        return $this;
    }
}
