<?php

namespace App\Console\Commands;

use App\DiskIo;
use App\Util\BasicFunctions;

use Illuminate\Console\Command;

class saveDiskIo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:diskIo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'creates a new entry for disk io statistics';

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
         * File /proc/diskstats
         * Column 0: device id
         * Column 1: partition id
         * Column 2: partition name -> save diskname
         * Column 3: amount of reads completed -> save read_io
         * Column 4 amount of reads merged (e.g. merge two consecutiv 4k to a single 8k)
         * Column 5: amount of sectors read -> save read_sector (512 = sector size)
         * Column 6: amount of milliseconds read
         * Column 7: amount of writes completed -> save write_io
         * Column 8: amount of writes merged
         * Column 9: amount of sectors written -> save write_sector
         * Column 10: amount of milliseconds writing
         * Column 11: current I/O in progress
         * Column 12: current milliseconds spent on I/O
         * Column 13: current weighted milliseconds spent on I/O
         */
        
        $statsFile = fopen("/proc/diskstats", "r");
        
        while(($line=fgets($statsFile))!==false) {
            $line = trim(str_replace("\n", "", $line));
            while(str_contains($line, "  "))
                $line = str_replace("  ", " ", $line);
            
            $exp = explode(" ", $line);
            
            if(BasicFunctions::startsWith($exp[2], "sd")) {
                $toSave = new DiskIo();
                $toSave->diskname = $exp[2];
                $toSave->read_io = intval($exp[3]);
                $toSave->read_sector = intval($exp[5]);
                $toSave->write_io = intval($exp[7]);
                $toSave->write_sector = intval($exp[9]);
                $toSave->save();
            }
        }
        
        fclose($statsFile);
    }
}
