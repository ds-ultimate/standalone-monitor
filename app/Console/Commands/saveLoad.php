<?php

namespace App\Console\Commands;

use App\Load;

use Illuminate\Console\Command;

class saveLoad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'creates a new entry for load statistics';

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
        static::do_saving();
    }
    
    public static function do_saving()
    {
        /*
         * Load
         * 1min / 5min / 15min - /proc/loadavg column 1-3
         */
        
        $statsFile = fopen("/proc/loadavg", "r");
        $line=fgets($statsFile);
        if($line === false) {
            die("Unable to read file");
        }
        $line = trim(str_replace("\n", "", $line));
        while(str_contains($line, "  "))
            $line = str_replace("  ", " ", $line);
        
        $exp = explode(" ", $line);
            
        $toSave = new Load();
        $toSave->oneMin = floatval($exp[0]);
        $toSave->fiveMin = floatval($exp[1]);
        $toSave->fifteenMin = floatval($exp[2]);
        $toSave->save();
        
        fclose($statsFile);
    }
}
