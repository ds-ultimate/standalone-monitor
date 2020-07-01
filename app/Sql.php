<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sql extends Model
{
    protected $fillable = [
        'bytes_received',
        'bytes_sent',
        'handler_commit',
        'handler_delete',
        'handler_update',
        'handler_write',
        'innodb_data_read',
        'innodb_data_written',
        'innodb_data_reads',
        'innodb_data_writes',
        'queries',
        'connections',

        'innodb_buffer_pool_bytes_data',
        'innodb_buffer_pool_pages_data',
        'innodb_buffer_pool_pages_free',
        'innodb_buffer_pool_pages_flushed',
        'innodb_mem_dictionary',
        'innodb_mem_total',

        'qcache_free_memory',
        'qcache_hits',
        'qcache_inserts',
        'qcache_not_cached',
        'qcache_total_blocks',
    ];
    
    protected $dates = [
        'updated_at',
        'created_at',
    ];
}
