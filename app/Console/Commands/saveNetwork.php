<?php

namespace App\Console\Commands;

use App\Network;

use Illuminate\Console\Command;

class saveNetwork extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:network';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'creates a new entry for network statistics';

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
         * Netzwerk
         * Interfaces: "ip -oneline link show" oder "ifconfig" oder ls /sys/class/net/
         * Gesendet Bytes -> /sys/class/net/[interface]/statistics/tx_bytes
         * Empfangen Bytes -> /sys/class/net/[interface]/statistics/rx_bytes
         * Gesendet Pakete -> /sys/class/net/[interface]/statistics/tx_packets
         * Empfangen Pakete -> /sys/class/net/[interface]/statistics/rx_packets
         */
        
        foreach(scandir("/sys/class/net") as $int) {
            if($int == "." || $int == "..")
                continue;
            
            $toSave = new Network();
            $toSave->interface = $int;
            $toSave->sent_bytes = intval(file_get_contents("/sys/class/net/$int/statistics/tx_bytes"));
            $toSave->received_bytes = intval(file_get_contents("/sys/class/net/$int/statistics/rx_bytes"));
            $toSave->sent_packets = intval(file_get_contents("/sys/class/net/$int/statistics/tx_packets"));
            $toSave->received_packets = intval(file_get_contents("/sys/class/net/$int/statistics/rx_packets"));
            $toSave->save();
        }
    }
}
