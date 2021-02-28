<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class backup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database backup';

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
        $filename='database_backup_'.date('G_a_m_d_y').'.sql';
        $result=exec('mysqldump '.env('DB_DATABASE').' --password='.env('DB_PASSWORD').' --user='.env("DB_USERNAME").' --single-transaction >/var/backups/'.$filename);
    }
}
