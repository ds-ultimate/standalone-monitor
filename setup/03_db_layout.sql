
CREATE TABLE `servers` (
 `server_id` int(10) unsigned NOT NULL,
 `time` bigint(20) NOT NULL,

 PRIMARY KEY (`server_id`)
);

CREATE TABLE `load` (
 `oneMin` double(8,2) NOT NULL,
 `fiveMin` double(8,2) NOT NULL,
 `fifteenMin` double(8,2) NOT NULL,

 `server_id` int(10) unsigned NOT NULL,
 `time` bigint(20) NOT NULL,
 `zoombase` tinyint NOT NULL,

 UNIQUE KEY `server_time` (`server_id`,`time`),
 UNIQUE KEY `server_time_zoom` (`server_id`,`time`,`zoombase`)
);

CREATE TABLE `memory` (
 `mem_total` int(20) NOT NULL,
 `file_cache_size` int(20) NOT NULL,
 `used_programms` int(20) NOT NULL,
 `used_buffers` int(20) NOT NULL,
 `used_cache` int(20) NOT NULL,
 `free` int(20) NOT NULL,

 `server_id` int(10) unsigned NOT NULL,
 `time` bigint(20) NOT NULL,
 `zoombase` tinyint NOT NULL,

 UNIQUE KEY `server_time` (`server_id`,`time`),
 UNIQUE KEY `server_time_zoom` (`server_id`,`time`,`zoombase`)
);

CREATE TABLE `network` (
 `interface` varchar(191) NOT NULL,
 `sent_bytes` int(20) NOT NULL,
 `received_bytes` int(20) NOT NULL,
 `sent_packets` int(20) NOT NULL,
 `received_packets` int(20) NOT NULL,

 `server_id` int(10) unsigned NOT NULL,
 `time` bigint(20) NOT NULL,
 `zoombase` tinyint NOT NULL,

 KEY `server_time` (`server_id`,`time`),
 KEY `server_time_zoom` (`server_id`,`time`,`zoombase`)
);

CREATE TABLE `diskusage` (
 `diskname` varchar(191) NOT NULL,
 `mounted_at` varchar(191) NOT NULL,
 `kbytes_all` int(20) NOT NULL,
 `kbytes_used` int(20) NOT NULL,
 `kbytes_reserved` int(20) NOT NULL,
 `inodes_all` int(20) NOT NULL,
 `inodes_used` int(20) NOT NULL,

 `server_id` int(10) unsigned NOT NULL,
 `time` bigint(20) NOT NULL,
 `zoombase` tinyint NOT NULL,

 KEY `server_time` (`server_id`,`time`),
 KEY `server_time_zoom` (`server_id`,`time`,`zoombase`)
);

CREATE TABLE `diskio` (
 `diskname` varchar(191) NOT NULL,
 `read_io` int(20) NOT NULL,
 `read_sector` int(20) NOT NULL,
 `write_io` int(20) NOT NULL,
 `write_sector` int(20) NOT NULL,

 `server_id` int(10) unsigned NOT NULL,
 `time` bigint(20) NOT NULL,
 `zoombase` tinyint NOT NULL,

 KEY `server_time` (`server_id`,`time`),
 KEY `server_time_zoom` (`server_id`,`time`,`zoombase`)
);

CREATE TABLE `cpu` (
 `name` varchar(191) NOT NULL,
 `all` int(20) NOT NULL,
 `user` int(20) NOT NULL,
 `user_niced` int(20) NOT NULL,
 `kernel` int(20) NOT NULL,
 `io_wait` int(20) NOT NULL,
 `idle` int(20) NOT NULL,

 `server_id` int(10) unsigned NOT NULL,
 `time` bigint(20) NOT NULL,
 `zoombase` tinyint NOT NULL,

 KEY `server_time` (`server_id`,`time`),
 KEY `server_time_zoom` (`server_id`,`time`,`zoombase`)
);

CREATE TABLE `sql` (
 `bytes_received` int(20) NOT NULL,
 `bytes_sent` int(20) NOT NULL,
 `handler_commit` int(20) NOT NULL,
 `handler_delete` int(20) NOT NULL,
 `handler_update` int(20) NOT NULL,
 `handler_write` int(20) NOT NULL,
 `innodb_data_read` int(20) NOT NULL,
 `innodb_data_written` int(20) NOT NULL,
 `innodb_data_reads` int(20) NOT NULL,
 `innodb_data_writes` int(20) NOT NULL,
 `queries` int(20) NOT NULL,
 `connections` int(20) NOT NULL,

 `innodb_buffer_pool_bytes_data` int(11) NOT NULL,
 `innodb_buffer_pool_pages_data` int(11) NOT NULL,
 `Innodb_buffer_pool_pages_dirty` int(11) NOT NULL,
 `Innodb_buffer_pool_bytes_dirty` int(11) NOT NULL,
 `innodb_buffer_pool_pages_free` int(11) NOT NULL,
 `innodb_buffer_pool_pages_flushed` int(11) NOT NULL,
 `innodb_mem_dictionary` int(11) NOT NULL,

 `qcache_free_memory` int(11) NOT NULL,
 `qcache_hits` int(20) NOT NULL,
 `qcache_inserts` int(20) NOT NULL,
 `qcache_not_cached` int(20) NOT NULL,
 `qcache_total_blocks` int(11) NOT NULL,

 `server_id` int(10) unsigned NOT NULL,
 `time` bigint(20) NOT NULL,
 `zoombase` tinyint NOT NULL,

 UNIQUE KEY `server_time` (`server_id`,`time`),
 UNIQUE KEY `server_time_zoom` (`server_id`,`time`,`zoombase`)
);

CREATE TABLE `ssh` (
 `num_sessions` int(11) NOT NULL,

 `server_id` int(10) unsigned NOT NULL,
 `time` bigint(20) NOT NULL,
 `zoombase` tinyint NOT NULL,

 UNIQUE KEY `server_time` (`server_id`,`time`),
 UNIQUE KEY `server_time_zoom` (`server_id`,`time`,`zoombase`)
);
