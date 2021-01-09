<?php

use App\DTO\RabbitMq\LogMessageDto;
use App\Mail\Orders\DeliverUserOrder;
use App\Mail\Orders\NotifyAdminForNewOrder;
use App\Models\Product;
use PhpAmqpLib\Message\AMQPMessage;

return [

    /*
    |--------------------------------------------------------------------------
    | Callback for logs queue
    |--------------------------------------------------------------------------
    |
    | Receives a message for logs queue and logs it with variant that was
    | passed in LogMessageDto
    |
    */

    'logCallback' => function (AMQPMessage $message) {

        $this->line(now()->format('Y-m-d H:i:s') . ' [x] Received message in logs queue...');

        $start = microtime(true);

        $messageBody = json_decode($message->body);

        switch ($messageBody->method) {
            case 'info' :
                logs()
                    ->channel($messageBody->channel)
                    ->info(
                        $messageBody->message,
                        (array)$messageBody->additionalInformation
                    );
                break;

            case 'notice' :
                logs()
                    ->channel($messageBody->channel)
                    ->notice(
                        $messageBody->message,
                        (array)$messageBody->additionalInformation
                    );
                break;

            case 'warning' :
                logs()
                    ->channel($messageBody->channel)
                    ->warning(
                        $messageBody->message,
                        (array)$messageBody->additionalInformation
                    );
                break;
        }

        $message->ack();

        $this->info(now()->format('Y-m-d H:i:s') . ' [x] Message was processed in ' . (microtime(true) - $start) . ' seconds in logs queue for ' . $messageBody->channel . ' channel...');
    },

    /*
    |--------------------------------------------------------------------------
    | Callback for admin email queue
    |--------------------------------------------------------------------------
    |
    | Receives a message about user order
    | Contains user and products that he has ordered
    | Sends a letter to admin to inform him about new order
    |
    */

    'adminEmailCallback' => function (AMQPMessage $message) {

        $this->line(now()->format('Y-m-d H:i:s') . ' [x] Received message in admin email queue...');

        $start = microtime(true);

        \Mail::send(new NotifyAdminForNewOrder(json_decode($message->body)));

        $this->info(now()->format('Y-m-d H:i:s') . ' [x] Message was processed in ' . (microtime(true) - $start) . ' seconds in email queue...');

        $message->ack();

        rabbitmq()->sendMessage(new LogMessageDto('emails', 'notice', 'Sended email to admins'), 'logs');
    },

    /*
    |--------------------------------------------------------------------------
    | Callback for user email queue
    |--------------------------------------------------------------------------
    |
    | Receives a message with products that user ordered
    | Sends a letter to user with keys to his products
    |
    */

    'userEmailCallback' => function (AMQPMessage $message) {

        $this->line(now()->format('Y-m-d H:i:s') . ' [x] Received message in users email queue...');

        $start = microtime(true);

        \Mail::send(new DeliverUserOrder(json_decode($message->body)));

        $this->info(now()->format('Y-m-d H:i:s') . ' [x] Message was processed in ' . (microtime(true) - $start) . ' seconds in email queue...');

        $message->ack();

        rabbitmq()->sendMessage(new LogMessageDto('emails', 'notice', 'Sended email'), 'logs');
    },

    /*
    |--------------------------------------------------------------------------
    | Callback for ElasticSearch queue
    |--------------------------------------------------------------------------
    |
    | Receives a message with product that needs to be added or updated
    | If for some reason the queue can't handle that message,
    | message is redirected to retry queue
    | After reaching amount of retries message is handled in different way
    |
    */

    'elasticDocumentCallback' => function (AMQPMessage $message) {

        $this->line(now()->format('Y-m-d H:i:s') . ' [x] Received message in elastic queue...');

        $start = microtime(true);

        $messageBody = json_decode($message->body);

        try {
            $product = Product::find($messageBody->documentId);

            elasticsearch()->addDocumentToIndex($messageBody->indexName, $product);

            $message->ack();

            $this->info(now()->format('Y-m-d H:i:s') . ' [x] Message was processed in ' . (microtime(true) - $start) . ' seconds in elastic queue...');

        } catch (\Exception $exception) {

            $this->warn(now()->format('Y-m-d H:i:s') . ' [-] Message is corrupted, sending it to retry queue...');

            if ($this->messageRejectedFirstTime($message)) {

                $error_message = new LogMessageDto('elastic', 'warning', 'Elastic', [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString()
                ]);

                rabbitmq()->sendMessage($error_message, 'logs');
            }

            if ($this->messageOutOfRetries($message)) { // Can be replaced by delivering this message to another queue
                $this->error(now()->format('Y-m-d H:i:s') . ' [x] Message was deleted after reaching retry limit...');

                $message->ack();
            } else {
                $message->nack();
            }
        }
    },
];
