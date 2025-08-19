<?php

// if seconds is dividable by x then this is active (or some value between last entry and this one)
// key is what will be saved into the database  - a little bit spacing between the keys for (maybe) later
$ZOOM_CONFIG = [
    5 => 24 * 60 * 60, // 1 day
    10 => 4 * 60 * 60, // 4 hours
    20 => 60 * 60, // 1 hour
    30 => 10 * 60, // 10 minutes
    40 => 60, // 1 minute
    50 => 5, // 5 seconds
    60 => 1, // 1 second
];

// Delete zoom level [key] after [value] seconds
$RETENTION_POLICY = [
    // 1 day data: don't delete that
    // 4 hours data: don't delete that
    // 1 hour data: don't delete that
    30 => 365 * 24 * 60 * 60, // 10 minutes data after 1 year
    40 => 60 * 24 * 60 * 60, // 1 minute data after 60 days
    50 => 7 * 24 * 60 * 60, // 5 seconds data after 7 days
    60 => 24 * 60 * 60, // 1 second data after 1 day
];

$COLLECTOR_CONFIG = [
    // config name
    "cpu" => [
        "type" => "array",
        "columns" => [
            // request_name, sql_name, type
            ["name", "name", "s"],
            ["all_raw", "all", "id"],
            ["user_raw", "user", "id"],
            ["user_niced_raw", "user_niced", "id"],
            ["kernel_raw", "kernel", "id"],
            ["io_wait_raw", "io_wait", "id"],
            ["idle_raw", "idle", "id"],
        ],
    ],
    "diskio" => [
        "type" => "array",
        "columns" => [
            ["diskname", "diskname", "s"],
            ["r_io", "read_io", "id"],
            ["r_sector", "read_sector", "id"],
            ["w_io", "write_io", "id"],
            ["w_sector", "write_sector", "id"],
        ],
    ],
    "diskusage" => [
        "type" => "array",
        "columns" => [
            ["diskname", "diskname", "s"],
            ["mounted_at", "mounted_at", "s"],
            ["kb_all", "kbytes_all", "i"],
            ["kb_used", "kbytes_used", "i"],
            ["kb_reserved", "kbytes_reserved", "i"],
            ["in_all", "inodes_all", "i"],
            ["in_used", "inodes_used", "i"],
        ],
    ],
    "load" => [
        "type" => "single",
        "columns" => [
            ["one", "oneMin", "f"],
            ["five", "fiveMin", "f"],
            ["fifteen", "fifteenMin", "f"],
        ],
    ],
    "memory" => [
        "type" => "single",
        "columns" => [
            ["mem_total", "mem_total", "i"],
            ["used_programs", "used_programs", "i"],
            ["used_buffers", "used_buffers", "i"],
            ["used_cache", "used_cache", "i"],
            ["free", "free", "i"],
        ],
    ],
    "network" => [
        "type" => "array",
        "columns" => [
            ["interface", "interface", "s"],
            ["sent_bytes", "sent_bytes", "id"],
            ["received_bytes", "received_bytes", "id"],
            ["sent_packets", "sent_packets", "id"],
            ["received_packets", "received_packets", "id"],
        ],
    ],
    "sql" => [
        "type" => "single",
        "columns" => [
            ["bytes_received", "bytes_received", "i"],
            ["bytes_sent", "bytes_sent", "i"],
            ["handler_commit", "handler_commit", "i"],
            ["handler_delete", "handler_delete", "i"],
            ["handler_update", "handler_update", "i"],
            ["handler_write", "handler_write", "i"],
            ["innodb_data_read", "innodb_data_read", "i"],
            ["innodb_data_written", "innodb_data_written", "i"],
            ["innodb_data_reads", "innodb_data_reads", "i"],
            ["innodb_data_writes", "innodb_data_writes", "i"],
            ["queries", "queries", "i"],
            ["connections", "connections", "i"],
    
            ["innodb_buffer_pool_bytes_data", "innodb_buffer_pool_bytes_data", "i"],
            ["innodb_buffer_pool_pages_data", "innodb_buffer_pool_pages_data", "i"],
            ["Innodb_buffer_pool_pages_dirty", "Innodb_buffer_pool_pages_dirty", "i"],
            ["Innodb_buffer_pool_bytes_dirty", "Innodb_buffer_pool_bytes_dirty", "i"],
            ["innodb_buffer_pool_pages_free", "innodb_buffer_pool_pages_free", "i"],
            ["innodb_buffer_pool_pages_flushed", "innodb_buffer_pool_pages_flushed", "i"],
            ["innodb_mem_dictionary", "innodb_mem_dictionary", "i"],
    
            ["qcache_free_memory", "qcache_free_memory", "i"],
            ["qcache_hits", "qcache_hits", "i"],
            ["qcache_inserts", "qcache_inserts", "i"],
            ["qcache_not_cached", "qcache_not_cached", "i"],
            ["qcache_total_blocks", "qcache_total_blocks", "i"],
        ],
    ],
    "ssh" => [
        "type" => "single",
        "columns" => [
            ["sessions", "num_sessions", "i"],
        ],
    ],
];
