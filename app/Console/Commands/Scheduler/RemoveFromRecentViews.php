<?php

namespace App\Console\Commands\Scheduler;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RemoveFromRecentViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recent:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes expired items from users recent views';

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
     * @return int
     */
    public function handle()
    {
        $users = Redis::hKeys('users');

        foreach ($users as $userList) {
            $list = json_decode(Redis::hGet('users', $userList), true);

            foreach ($list as $key => $item) {
                if ($item['expire'] >= now()->timestamp) {
                    unset($list[$key]);
                }
            }

            Redis::hSet('users', $userList, json_encode(array_values($list)));
        }
    }
}
