<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Workerman\Worker;

class WebSocket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts a web socket';

    /**
     * The socket that is now working
     *
     * @var Worker
     */
    private $socket;

    private $connections = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->socket = new Worker('websocket://localhost:8000');
        $this->socket->count = 4;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->socket->onWorkerStart = function () {
            echo 'Socket started....' . $this->newLine();

            $this->start();
        };

        $this->socket->onClose = function ($connection) {
            if(in_array($connection, $this->connections)){
                unset($this->connections[array_search($connection, $this->connections)]);
                echo 'User with id ' . $connection->user_id . ' has disconnected' . $this->newLine();
            }

            $this->connections = array_values($this->connections);

            foreach ($this->connections as $user) {
                $user->send($this->makeUsersArray($this->connections));
            }
        };

        Worker::runAll();
    }

    private function start()
    {
        $this->socket->onWebSocketConnect = function ($connection) {

            $connection->date = strtotime(now()->toString());

            if (isset($_GET['user_id'])) {
                $connection->user_id = $_GET['user_id'];
            } else {
                $connection->user_id = 'guest';
            }

            if (!in_array($connection->user_id, $this->connections)) {
                $this->connections[] = $connection;
            }

            echo 'User with id ' . $connection->user_id . ' has connected' . $this->newLine();

            foreach ($this->connections as $user) {
                $user->send($this->makeUsersArray($this->connections));
            }
        };
    }

    private function makeUsersArray(array $connections){
        $users = [];

        foreach ($connections as $connection) {
            $users[] = $connection->user_id;
        }

        return json_encode($users);
    }
}
