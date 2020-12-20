<?php

namespace App\Console\Commands;

use App\DTO\RabbitMq\LogMessageDto;
use App\Mail\Orders\DeliverUserOrder;
use App\Mail\Orders\NotifyAdminForNewOrder;
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
        // Exchanges declaration
        rabbitmq()->makeExchange('logs', 'direct', false, true);
        rabbitmq()->makeExchange('email', 'direct', false, true);

        // Queues declaration
        rabbitmq()->makeQueue('logs_queue', false, true);
        rabbitmq()->makeQueue('admin_email_queue', false, true);
        rabbitmq()->makeQueue('email_queue', false, true);

        // Binding queues to exchanges
        rabbitmq()->bindQueueToExchange('logs_queue', 'logs');
        rabbitmq()->bindQueueToExchange('admin_email_queue', 'email', 'admins');
        rabbitmq()->bindQueueToExchange('email_queue', 'email', 'users');

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

            echo now()->format('Y-m-d H:i:s') . ' [x] Message was processed in ' . (microtime(true) - $start) . ' seconds in logs queue...';

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

        rabbitmq()->consume([
            ['queueName' => 'logs_queue', 'callback' => $logsCallback, 'logs'],
            ['queueName' => 'admin_email_queue', 'callback' => $adminEmailCallback, 'admin_email_queue'],
            ['queueName' => 'email_queue', 'callback' => $userEmailCallback, 'user_email_queue'],
        ]);

        rabbitmq()->closeConnections();
    }
}
