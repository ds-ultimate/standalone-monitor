<?php

namespace App\Console\Commands;

use App\Sql;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class saveSql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:sql';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'creates a new entry for SQL statistics';

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
         * via show global status (comulativ values)
         * Bytes received -> bytes_received
         * Bytes sent -> bytes_sent
         * Handler commit -> handler_commit
         * Handler delete -> handler_delete
         * Handler update -> handler_update
         * Handler write -> handler_write
         * Innodb buffer pool bytes data -> innodb_buffer_pool_bytes_data
         * Innodb buffer pool pages data -> innodb_buffer_pool_pages_data
         * Innodb buffer pool pages free -> innodb_buffer_pool_pages_free
         * Innodb buffer pool pages flushed -> innodb_buffer_pool_pages_flushed
         * Innodb data read -> innodb_data_read
         * Innodb data written-> innodb_data_written
         * Innodb data reads -> innodb_data_reads
         * Innodb data writes -> innodb_data_writes
         * Innodb mem dictionary -> innodb_mem_dictionary
         * Innodb mem total -> innodb_mem_total
         * Qcache free memory -> qcache_free_memory
         * Qcache hits -> qcache_hits
         * Qcache inserts -> qcache_inserts
         * Qcache not cached -> qcache_not_cached
         * Qcache total blocks -> qcache_total_blocks
         * Queries -> queries
         * Connections -> connections
         */
        
        $mapping = [
            "Bytes_received" => "bytes_received",
            "Bytes_sent" => "bytes_sent",
            "Handler_commit" => "handler_commit",
            "Handler_delete" => "handler_delete",
            "Handler_update" => "handler_update",
            "Handler_write" => "handler_write",
            "Innodb_data_read" => "innodb_data_read",
            "Innodb_data_written" => "innodb_data_written",
            "Innodb_data_reads" => "innodb_data_reads",
            "Innodb_data_writes" => "innodb_data_writes",
            "Queries" => "queries",
            "Connections" => "connections",
            
            "Innodb_buffer_pool_bytes_data" => "innodb_buffer_pool_bytes_data",
            "Innodb_buffer_pool_pages_data" => "innodb_buffer_pool_pages_data",
            "Innodb_buffer_pool_pages_free" => "innodb_buffer_pool_pages_free",
            "Innodb_buffer_pool_pages_flushed" => "innodb_buffer_pool_pages_flushed",
            "Innodb_mem_dictionary" => "innodb_mem_dictionary",
            "Innodb_mem_total" => "innodb_mem_total",
            
            "Qcache_free_memory" => "qcache_free_memory",
            "Qcache_hits" => "qcache_hits",
            "Qcache_inserts" => "qcache_inserts",
            "Qcache_not_cached" => "qcache_not_cached",
            "Qcache_total_blocks" => "qcache_total_blocks",
        ];
        
        $toSave = new Sql();
        foreach(DB::select("SHOW GLOBAL STATUS") as $statusVar) {
            if(array_key_exists($statusVar->Variable_name, $mapping)) {
                //will be written directly to Database
                $toSave->{$mapping[$statusVar->Variable_name]} = $statusVar->Value;
            }
        }
        $toSave->save();
    }
}
