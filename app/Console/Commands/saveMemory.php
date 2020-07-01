<?php

namespace App\Console\Commands;

use App\Memory;

use Illuminate\Console\Command;

class saveMemory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:memory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'creates a new entry for memory statistics';

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
         * Memory /proc/meminfo
         * total memory -> MemTotal
         * fileCacheSize -> Active(file)+Inactive(file)
         * used (programms) -> MemTotal - MemFree - Buffers - Cached
         * used (buffers) -> Buffers
         * used (cache) -> Cached
         * free -> MemFree
         * claimable (not save) -> used (buffers) + used (cache) + free
         */
        
        $statsFile = fopen("/proc/meminfo", "r");
        
        $mapping = [
            "MemTotal:" => "mem_total",
            "MemFree:" => "free",
            "Buffers:" => "used_buffers",
            "Cached:" => "used_cache",
        ];
        
        $toSave = new Memory();
        $prog = 0;
        $fileCache = 0;
        while(($line=fgets($statsFile))!==false) {
            $line = trim(str_replace("\n", "", $line));
            while(str_contains($line, "  "))
                $line = str_replace("  ", " ", $line);
            
            $exp = explode(" ", $line);
            
            if(array_key_exists($exp[0], $mapping)) {
                //will be written directly to Database
                $toSave->{$mapping[$exp[0]]} = static::inKB($exp[1], $exp[2]);
            }
            switch($exp[0]) {
                case "Active(file):":
                case "Inactive(file):":
                    $fileCache += static::inKB($exp[1], $exp[2]);
                    break;
                case "MemTotal:":
                    $prog += static::inKB($exp[1], $exp[2]);
                    break;
                case "MemFree:":
                case "Buffers:":
                case "Cached:":
                    $prog -= static::inKB($exp[1], $exp[2]);
                    break;
            }
        }
        $toSave->file_cache_size = $fileCache;
        $toSave->used_programms = $prog;
        $toSave->save();
        
        fclose($statsFile);
    }
    
    private static function inKB($val, $strExp) {
        switch($strExp) {
            case "MB":
                return intval(1000 * floatval($val));
            case "GB":
                return intval(1000 * 1000 * floatval($val));
            case "kB":
            default:
                return intval($val);
        }
    }
}
