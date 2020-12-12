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
    protected $signature = 'websocket:serve';

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
            foreach ($this->connections as $index => $currentConnection) {
                if($connection->user_id === $currentConnection->user_id){
                    unset($this->connections[$index]);
                }
            }

            $this->connections = array_values($this->connections);

            foreach ($this->connections as $user) {
                $user->send($this->makeUsersArray($this->connections));
            }
        };

        Worker::runAll();
    }

    /**
     * Connection handler
     */
    private function start()
    {
        $this->socket->onWebSocketConnect = function ($connection) {

            if (isset($_GET['user_id'])) {
                $connection->user_id = $_GET['user_id'];
            } else {
                $connection->user_id = 'guest';
            }

            $connection->date = now()->format('d.m.Y H:i:s');
            $connection->page = $_GET['on'];

            if(empty($this->connections)){
                $this->connections[] = $connection;
            }

            echo 'User with id ' . $connection->user_id . ' has connected' . $this->newLine();

            foreach ($this->connections as $user) {
                if($user->user_id !== $connection->user_id){
                    $this->connections[] = $connection;
                }

                $user->send($this->makeUsersArray($this->connections));
            }
        };
    }

    /**
     * Make message for socket
     *
     * @param array $connections
     * @return false|string
     */
    private function makeUsersArray(array $connections){
        $users = [];

        foreach ($connections as $connection) {
            $users[] = ['id' => $connection->user_id, 'date' => $connection->date, 'page' => $connection->page];
        }

        return json_encode($users);
    }
}
