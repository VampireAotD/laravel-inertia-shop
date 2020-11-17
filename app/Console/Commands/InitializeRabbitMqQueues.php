<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Message\AMQPMessage;

class InitializeRabbitMqQueues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbit:serve';

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
        // Queues declaration
        rabbitmq()->makeQueue('logs_queue', false, true);
        rabbitmq()->makeQueue('email_queue', false, true);

        // Exchanges declaration
        rabbitmq()->makeExchange('logs', 'direct', false, true);
        rabbitmq()->makeExchange('email', 'direct', false, true);

        // Binding queues to exchanges
        rabbitmq()->bindQueueToExchange('logs_queue', 'logs');
        rabbitmq()->bindQueueToExchange('email_queue', 'email');

        echo 'Starting all queues....';

        $this->newLine(2);

        // Callbacks
        $logsCallback = function (AMQPMessage $message) {

            echo now()->format('Y-m-d H:i:s') . ' Received message in logs queue...';

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

            echo now()->format('Y-m-d H:i:s') . ' Message was processed in ' . (microtime(true) - $start) . ' seconds in logs queue...';

            $this->newLine();
        };

        $emailCallback = function (AMQPMessage $message) {
            // TODO : Implement email queue logic
        };

        rabbitmq()->consume([
            ['queueName' => 'logs_queue', 'callback' => $logsCallback],
            ['queueName' => 'email_queue', 'callback' => $emailCallback],
        ]);

        rabbitmq()->closeConnections();
    }
}
