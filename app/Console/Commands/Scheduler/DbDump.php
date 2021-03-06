<?php

namespace App\Console\Commands\Scheduler;

use Illuminate\Console\Command;

class DbDump extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:dump {path=database/dump/} {filename=dump} {--single=true} {--separate=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump database
                                Path argument - is a path to folder which will contain dump files.
                                Filename argument - name of a dumped file (only for single option).
                                If one file option is true, it will dump in a single file.
                                If separate is true it will dump all the tables in different files and will name them by table name.';

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
        $db = config('database.connections.mysql.database');
        $user = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $directory = rtrim($this->argument('path'), '/');

        if (!realpath($directory) && !is_dir($directory)) {
            mkdir($directory);
        }

        $backup = base_path($directory . DIRECTORY_SEPARATOR . $this->argument('filename') . date('dmY') . '.sql');

        if ($this->option('single') === 'true') {
            $this->line('[x] Creating dump file with all tables...');

            exec("mysqldump --user=$user --password=$password --databases $db > $backup");

            $this->line('[x] Dump file has been created!');
        }

        if ($this->option('separate') === 'true') {
            $this->line('[x] Creating dump file for each table in database...');

            $this->newLine();

            exec("mysqldump --user=$user --password=$password --tab=$backup $db");

            $this->line('[x] Dump file has been created!');
        }

        return true;
    }
}
