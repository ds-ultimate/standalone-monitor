<?php

namespace App\Console\Commands;

use App\Cpu;
use App\Util\BasicFunctions;

use Illuminate\Console\Command;

class saveCpu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:cpu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'creates a new entry for cpu statistics';

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
         * Cpu /proc/stat | grep cpu
         * Column 0: cpu if all or cpu#id for the cores
         * Column 1: user mode code
         * Column 2: niced (low prio) user mode code
         * Column 3: kernel mode code
         * Column 4: idle
         * Column 5: iowait
         * Column 6: servicing interrupts
         * Column 7: servicing softirqs
         * Column 8: steal (time in other OS)
         * Column 9: guest (time used running guest code)
         * Column 10: guest_nice
         * All accumulated = whole time * 100 ("User_Hz")
         */
        
        $statsFile = fopen("/proc/stat", "r");
        
        while(($line=fgets($statsFile))!==false) {
            $line = trim(str_replace("\n", "", $line));
            while(str_contains($line, "  "))
                $line = str_replace("  ", " ", $line);
            
            $exp = explode(" ", $line);
            
            if(BasicFunctions::startsWith($exp[0], "cpu")) {
                $toSave = new Cpu();
                $all = 0;
                for($i = 1; $i < 11; $i++) {
                    $exp[$i] = intval($exp[$i]);
                    $all+= $exp[$i];
                }
                $toSave->name = $exp[0];
                $toSave->all = $all;
                $toSave->user = $exp[1];
                $toSave->user_niced = $exp[2];
                $toSave->kernel = $exp[3];
                $toSave->io_wait = $exp[5];
                $toSave->idle = $exp[4];
                $toSave->save();
            }
        }
        
        fclose($statsFile);
    }
}
