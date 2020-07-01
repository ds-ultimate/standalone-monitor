<?php

namespace App\Console\Commands;

use App\Ssh;
use App\Util\BasicFunctions;

use Illuminate\Console\Command;

class saveSsh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:ssh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'creates a new entry for ssh statistics';

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
        (SSH Usage open sessions)
        ps -A x | grep [s]shd
         */
        
        $numSession = 0;
        $out = [];
        exec("ps -ax | grep [s]shd", $out);
        
        foreach($out as $line) {
            while(str_contains($line, "  "))
                $line = str_replace("  ", " ", $line);
            $line = trim($line);
            if($line == "") continue;
            
            $exploded = explode(" ", $line);
            if(count($exploded) < 6) continue;
            if($exploded[4] != "sshd:") continue;
            
            $shell = explode("@", $exploded[5]);
            if(count($shell) < 2) continue;
            if(! BasicFunctions::startsWith($shell[1], "pts")) continue;
            $numSession += 1;
        }
        $toSave = new Ssh();
        $toSave->num_sessions = $numSession;
        $toSave->save();
    }
}
