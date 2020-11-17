<?php

namespace App\DTO\RabbitMq;

use App\DTO\DataTransferObject;

class LogMessageDto extends DataTransferObject
{
    /**
     * Channel to publish messages
     *
     * @var string
     */
    private string $channel;

    /**
     * Method to use
     *
     * @var string
     */
    private string $method;

    /**
     * Message for log
     *
     * @var string
     */
    private string $message;

    /**
     * Some additional information
     *
     * @var array
     */
    private array $additionalInformation;

    public function __construct(
        $channel = 'single',
        $method = 'notice',
        $message = 'Empty log message',
        $additionalInformation = []
    )
    {
        $this->setChannel($channel);
        $this->setMethod($method);
        $this->setMessage($message);
        $this->setAdditionalInformation($additionalInformation);
    }

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @param string $channel
     * @return LogMessageDto
     */
    public function setChannel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return LogMessageDto
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return LogMessageDto
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return array
     */
    public function getAdditionalInformation(): array
    {
        return $this->additionalInformation;
    }

    /**
     * @param array $additionalInformation
     * @return LogMessageDto
     */
    public function setAdditionalInformation(array $additionalInformation): self
    {
        $this->additionalInformation = $additionalInformation;

        return $this;
    }
}
