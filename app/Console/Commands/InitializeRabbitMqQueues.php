<?php

namespace App\Console\Commands;

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
     * Array of callbacks
     *
     * @var array
     */
    private $callbacks;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->callbacks = include('Assets/RabbitMq/callbacks.php');
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

        $this->line('Starting all queues....');

        $this->newLine();

        rabbitmq()->consume([
            ['queueName' => 'logs_queue', 'callback' => $this->callbacks['logCallback'], 'logs'],
            ['queueName' => 'admin_email_queue', 'callback' => $this->callbacks['adminEmailCallback'], 'admin_email_queue'],
            ['queueName' => 'email_queue', 'callback' => $this->callbacks['userEmailCallback'], 'user_email_queue'],
            ['queueName' => 'elastic_documents', 'callback' => $this->callbacks['elasticDocumentCallback'], 'elastic_documents'],
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
