<?php

namespace App\Helpers\RabbitMq;

use App\DTO\DataTransferObject;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMq
{
    /**
     * Connection to RabbitMq
     *
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * Channel to work with
     *
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    private $channel;

    public function __construct(
        string $host,
        string $port,
        string $user,
        string $password
    )
    {
        $this->connection = new AMQPStreamConnection($host, $port, $user, $password);

        $this->channel = $this->connection->channel();
    }

    /**
     * Create a queue
     *
     * @param string $name        | Name of the queue
     * @param bool $passive       | If there is a queue with this name, than throw an Exception
     * @param bool $durable       | If server is down, delete queue
     * @param bool $exclusive     | Should this queue be visible to other channels
     * @param bool $autoDelete    | Delete queue after consuming
     * @param bool $noWait        | Wait for server response
     * @return array|null
     */
    public function makeQueue(
        string $name,
        bool $passive = false,
        bool $durable = false,
        bool $exclusive = false,
        bool $autoDelete = false,
        bool $noWait = false
    )
    {
        return $this->channel->queue_declare($name, $passive, $durable, $exclusive, $autoDelete, $noWait);
    }

    /**
     * Create an exchange
     *
     * @param string $name        | Name of exchange
     * @param string $type        | Type of exchange
     * @param bool $passive       | If there is an exchange with this name, than throw an Exception
     * @param bool $durable       | If server is down, delete exchange
     * @param bool $autoDelete    | Delete queue after consuming
     * @param bool $internal
     * @param bool $noWait        | Wait for server response
     * @return mixed|null
     */
    public function makeExchange(
        string $name,
        string $type,
        bool $passive = false,
        bool $durable = false,
        bool $autoDelete = false,
        bool $internal = false,
        bool $noWait = false
    )
    {
        return $this->channel->exchange_declare($name, $type, $passive, $durable, $autoDelete, $internal, $noWait);
    }

    /**
     * Bind exchange to queue
     *
     * @param string $queueName
     * @param string $exchangeName
     * @param string $routingKey
     * @return mixed|null
     */
    public function bindQueueToExchange(string $queueName, string $exchangeName, string $routingKey = '')
    {
        return $this->channel->queue_bind($queueName, $exchangeName, $routingKey);
    }

    /**
     * Send message to queue
     *
     * @param DataTransferObject|string $messageBody
     * @param string $exchangeName
     * @param string $routingKey
     * @param array $options
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function sendMessage(
        $messageBody,
        string $exchangeName,
        string $routingKey = '',
        array $options = [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]
    ) : void
    {
        if ($messageBody instanceof DataTransferObject) {
            $messageBody = $messageBody->convert('json');
        }

        $message = new AMQPMessage($messageBody, $options);

        $this->channel->basic_publish($message, $exchangeName, $routingKey);
    }

    /**
     * Read one message from queue
     *
     * @param string $queueName     | Name of the queue to consume
     * @param \Closure $callable    | Callback function that need to handle incoming message
     * @param string $consumerTag   | Name of the consumer
     * @param bool $noLocal         | Don't receive messages published by this consumer
     * @param bool $noAck           | If we read message from this queue, delete it automatically
     * @param bool $exclusive       | Only this consumer can access queue
     * @param bool $noWait
     * @return string
     */
    public function readMessage(
        string $queueName,
        \Closure $callable,
        string $consumerTag = '',
        bool $noLocal = false,
        bool $noAck = false,
        bool $exclusive = false,
        bool $noWait = false
    )
    {
        return $this->channel->basic_consume($queueName, $consumerTag, $noLocal, $noAck, $exclusive, $noWait, $callable);
    }

    /**
     * Consume all queue messages
     *
     * @param array $queues
     * @throws \ErrorException
     */
    public function consume(array $queues)
    {
        foreach ($queues as $queue) {
            echo "[*] Now listening to [${queue['queueName']}] queue......." . PHP_EOL;

            $this->readMessage(
                $queue['queueName'],
                $queue['callback'],
                $queue['consumerTag'] ?? ''
            );
        }

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    /**
     * Close all connections to RabbitMQ
     */
    public function closeConnections()
    {
        try{
            $this->channel->close();
            $this->connection->close();
        }catch (\Exception $exception){
            dd('Cannot close connection to Rabbit');
        }
    }
}
