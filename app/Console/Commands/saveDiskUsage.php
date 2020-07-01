<?php

namespace App\Console\Commands;

use App\DiskUsage;
use App\Util\BasicFunctions;

use Illuminate\Console\Command;

class saveDiskUsage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:diskUsage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'creates a new entry for disk usage statistics';

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
         * 1. /proc/mounts
         * 1-> type
         * 2-> location
         * 
         * 2. stat --file-system -c "%S %b %f %a %c %d"
         * %S ... block größe
         * %b ... gesBlöcke
         * %f ... freiBlöcke (super user)
         * %a ... freiBlöcke (non super user)
         * %c ... gesInodes
         * %d ... freiInodes
         * 
         * %f - %a = reserved
         */
        
        $mountsFile = fopen("/proc/mounts", "r");
        
        while(($line=fgets($mountsFile))!==false) {
            $line = trim(str_replace("\n", "", $line));
            while(str_contains($line, "  "))
                $line = str_replace("  ", " ", $line);
            
            $exp = explode(" ", $line);
            
            if(BasicFunctions::startsWith($exp[0], "/dev/")) {
                $fsStats = explode(" ", exec('stat --file-system -c "%S %b %f %a %c %d" \''.$exp[1].'\''));
                $block = floatval($fsStats[0]) / 1024;
                
                $toSave = new DiskUsage();
                $toSave->diskname = str_replace("/dev/", "", $exp[0]);
                $toSave->mounted_at = $exp[1];
                $toSave->kbytes_all = intval($fsStats[1]) * $block;
                $toSave->kbytes_used = (intval($fsStats[1]) - intval($fsStats[2])) * $block;
                $toSave->kbytes_reserved = (intval($fsStats[2]) - intval($fsStats[3])) * $block;
                $toSave->inodes_all = intval($fsStats[4]);
                $toSave->inodes_used = intval($fsStats[4]) - intval($fsStats[5]);
                $toSave->save();
            }
        }
        
        fclose($mountsFile);
   }
}
