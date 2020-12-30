<?php

namespace App\Console\Commands;

use App\DTO\RabbitMq\LogMessageDto;
use App\Mail\Orders\DeliverUserOrder;
use App\Mail\Orders\NotifyAdminForNewOrder;
use App\Models\Product;
use Illuminate\Console\Command;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class InitializeRabbitMqQueues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbit:serve {--retry=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs RabbitMQ queues';

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
     * @return void
     * @throws \ErrorException
     * @throws \Exception
     */
    public function handle()
    {
        // Exchanges declaration
        rabbitmq()->makeExchange('logs', 'direct', false, true);
        rabbitmq()->makeExchange('email', 'direct', false, true);
        rabbitmq()->makeExchange('elastic', 'direct', false, true);

        // Queues declaration
        rabbitmq()->makeQueue('logs_queue', false, true);
        rabbitmq()->makeQueue('admin_email_queue', false, true);
        rabbitmq()->makeQueue('email_queue', false, true);
        rabbitmq()->makeQueue('elastic_documents', false, true, false, false, false, [
            'x-dead-letter-exchange' => 'elastic',
            'x-dead-letter-routing-key' => 'retry'
        ]);
        rabbitmq()->makeQueue('elastic_retry', false, true, false, false, false, [
            'x-dead-letter-exchange' => 'elastic',
            'x-dead-letter-routing-key' => 'documents',
            'x-message-ttl' => 1800
        ]);

        // Binding queues to exchanges
        rabbitmq()->bindQueueToExchange('logs_queue', 'logs');
        rabbitmq()->bindQueueToExchange('admin_email_queue', 'email', 'admins');
        rabbitmq()->bindQueueToExchange('email_queue', 'email', 'users');
        rabbitmq()->bindQueueToExchange('elastic_documents', 'elastic', 'documents');
        rabbitmq()->bindQueueToExchange('elastic_retry', 'elastic', 'retry');

        echo 'Starting all queues....';

        $this->newLine(2);

        // Callbacks
        $logsCallback = function (AMQPMessage $message) {

            echo now()->format('Y-m-d H:i:s') . ' [x] Received message in logs queue...';

            $start = microtime(true);

            $this->newLine();

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

            echo now()->format('Y-m-d H:i:s') . ' [x] Message was processed in ' . (microtime(true) - $start) . ' seconds in logs queue for ' . $messageBody->channel . ' channel...';

            $this->newLine();
        };

        $adminEmailCallback = function (AMQPMessage $message) {

            echo now()->format('Y-m-d H:i:s') . ' [x] Received message in admin email queue...';

            $start = microtime(true);

            $this->newLine();

            \Mail::send(new NotifyAdminForNewOrder(json_decode($message->body)));

            echo now()->format('Y-m-d H:i:s') . ' [x] Message was processed in ' . (microtime(true) - $start) . ' seconds in email queue...';

            $this->newLine();

            $message->ack();

            rabbitmq()->sendMessage(new LogMessageDto('emails', 'notice', 'Sended email to admins'), 'logs');
        };

        $userEmailCallback = function (AMQPMessage $message) {
            echo now()->format('Y-m-d H:i:s') . ' [x] Received message in users email queue...';

            $start = microtime(true);

            $this->newLine();

            \Mail::send(new DeliverUserOrder(json_decode($message->body)));

            echo now()->format('Y-m-d H:i:s') . ' [x] Message was processed in ' . (microtime(true) - $start) . ' seconds in email queue...';

            $this->newLine();

            $message->ack();

            rabbitmq()->sendMessage(new LogMessageDto('emails', 'notice', 'Sended email'), 'logs');
        };

        $elasticDocumentCallback = function (AMQPMessage $message) {
            echo now()->format('Y-m-d H:i:s') . ' [x] Received message in elastic queue...';

            $start = microtime(true);

            $this->newLine();

            $messageBody = json_decode($message->body);

            try {
                $product = Product::find($messageBody->documentId);

                elasticsearch()->addDocumentToIndex($messageBody->indexName, $product);

                $message->ack();

                echo now()->format('Y-m-d H:i:s') . ' [x] Message was processed in ' . (microtime(true) - $start) . ' seconds in elastic queue...';

                $this->newLine();
            } catch (\Exception $exception) {
                echo now()->format('Y-m-d H:i:s') . ' [-] Message is corrupted, sending it to retry queue...';

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
                    $this->newLine();

                    echo now()->format('Y-m-d H:i:s') . ' [X] Message was deleted after reaching retry limit...';

                    $message->ack();
                } else {
                    $message->nack();
                }

                $this->newLine();
            }
        };

        rabbitmq()->consume([
            ['queueName' => 'logs_queue', 'callback' => $logsCallback, 'logs'],
            ['queueName' => 'admin_email_queue', 'callback' => $adminEmailCallback, 'admin_email_queue'],
            ['queueName' => 'email_queue', 'callback' => $userEmailCallback, 'user_email_queue'],
            ['queueName' => 'elastic_documents', 'callback' => $elasticDocumentCallback, 'elastic_documents'],
        ]);

        rabbitmq()->closeConnections();
    }

    /**
     * Return true if message was rejected for some reason
     *
     * @param AMQPMessage $message
     * @return bool
     */
    protected function messageRejectedFirstTime(AMQPMessage $message)
    {
        if (array_key_exists('application_headers', $message->get_properties())) {
            /**
             * @var $data AMQPTable
             */
            $data = $message->get('application_headers');

            return $data->getNativeData()['x-first-death-reason'] === 'rejected'
                && $data->getNativeData()['x-death'][0]['count'] === 1;
        }

        return false;
    }

    /**
     * Return true if message was rejected and retries counter move than were in option
     *
     * @param AMQPMessage $message
     * @return bool
     */
    protected function messageOutOfRetries(AMQPMessage $message)
    {
        if (array_key_exists('application_headers', $message->get_properties())) {
            /**
             * @var $data AMQPTable
             */
            $data = $message->get('application_headers');

            return $data->getNativeData()['x-first-death-reason'] === 'rejected'
                && $data->getNativeData()['x-death'][0]['count'] >= $this->option('retry');
        }

        return false;
    }
}
