<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class CronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:Cronjob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update time every minute using cron job';

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
     * @return mixed
     */
    public function handle()
    {
        //\File::put(public_path('info'), \Log::info('message'));

        DB::table('count_time')->insert([
            'time' => date("h:i:s")
        ]);
    }
}
