<?php

namespace App\Console\Commands;



use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class callBackApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process-callback';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To initiate Callback Apis for android and apple';

    /**
     * Execute the console command.
     *
     * @return int
     */


    public function __construct()
    {
        parent::__construct();

    }

    public function handle()
    {

        $now = date('Y-m-d H:i:s');
        $nowPlus = date("Y-m-d H:i:s", strtotime("+5 minutes"));
        $nowMinus = date("Y-m-d H:i:s", strtotime("-1 minutes"));


        //$this->info('now ===== ' . $now);
        //$this->info('now plus 1 minute ' . $nowPlus);
        $this->info('now plus 1 minute ' . $nowMinus);
        //exit;

        //$this->log('markasfailed:run started at ' . $now);
        $this->info('process-callback started at ' . $now);
        $this->log('process-callback started at ' . $now);

        DB::enableQueryLog();
        $queryStr = ""; // any query from database

        $result = DB::select($queryStr, [$nowPlus]);

        #TODO  --uncomment/remove to debug/prod
        $queries = DB::getQueryLog(); //dd($queries);
        $this->log(json_encode($queries));

        $totalUsers = count($result);

        $limit = env('CALLBACK_MILESTONE', 5);
        $loops = ceil($totalUsers / $limit);

        $this->info("total loops ===>  " . $loops);
        $this->log("total loops ===>  " . $loops);

        $offset = 0;
        for ($i = 0; $i < $loops; $i++) {
            $this->info("offset, limit ===>  " . $offset . "  == " . $limit);
            $this->log("offset, limit ===>  " . $offset . "  == " . $limit);
            $data = array_slice($result, $offset, $limit);

            $this->processRequests($data, $limit, $offset);

            $offset = $limit + $offset;
        }
        $this->info('process-callback ended which was started at ' . $now);
        $this->log('process-callback ended which was started at ' . $now);
    }


    private function processRequests($data, $limit, $offset)
    {

        foreach ($data as $key => $datum) {

            $index = $offset + $key + 1;

            $this->info("loop start for " . $index);
            $this->log("loop start for " . $index);


        }


    }

    public function log($msg)
    {
        \Log::channel('cronLogs')->info($msg);
        //Log::info($msg);
    }

    public function info($string, $verbosity = null)
    {
        $this->line($string, 'info', $verbosity);
    }


}
