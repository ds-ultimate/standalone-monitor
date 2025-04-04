
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
 `mem_total` bigint(20) NOT NULL,
 `file_cache_size` bigint(20) NOT NULL,
 `used_programms` bigint(20) NOT NULL,
 `used_buffers` bigint(20) NOT NULL,
 `used_cache` bigint(20) NOT NULL,
 `free` bigint(20) NOT NULL,

 `server_id` int(10) unsigned NOT NULL,
 `time` bigint(20) NOT NULL,
 `zoombase` tinyint NOT NULL,

 UNIQUE KEY `server_time` (`server_id`,`time`),
 UNIQUE KEY `server_time_zoom` (`server_id`,`time`,`zoombase`)
);

CREATE TABLE `network` (
 `interface` varchar(191) NOT NULL,
 `sent_bytes` bigint(20) NOT NULL,
 `received_bytes` bigint(20) NOT NULL,
 `sent_packets` bigint(20) NOT NULL,
 `received_packets` bigint(20) NOT NULL,

 `server_id` int(10) unsigned NOT NULL,
 `time` bigint(20) NOT NULL,
 `zoombase` tinyint NOT NULL,

 KEY `server_time` (`server_id`,`time`),
 KEY `server_time_zoom` (`server_id`,`time`,`zoombase`)
);

CREATE TABLE `diskusage` (
 `diskname` varchar(191) NOT NULL,
 `mounted_at` varchar(191) NOT NULL,
 `kbytes_all` bigint(20) NOT NULL,
 `kbytes_used` bigint(20) NOT NULL,
 `kbytes_reserved` bigint(20) NOT NULL,
 `inodes_all` bigint(20) NOT NULL,
 `inodes_used` bigint(20) NOT NULL,

 `server_id` int(10) unsigned NOT NULL,
 `time` bigint(20) NOT NULL,
 `zoombase` tinyint NOT NULL,

 KEY `server_time` (`server_id`,`time`),
 KEY `server_time_zoom` (`server_id`,`time`,`zoombase`)
);

CREATE TABLE `diskio` (
 `diskname` varchar(191) NOT NULL,
 `read_io` bigint(20) NOT NULL,
 `read_sector` bigint(20) NOT NULL,
 `write_io` bigint(20) NOT NULL,
 `write_sector` bigint(20) NOT NULL,

 `server_id` int(10) unsigned NOT NULL,
 `time` bigint(20) NOT NULL,
 `zoombase` tinyint NOT NULL,

 KEY `server_time` (`server_id`,`time`),
 KEY `server_time_zoom` (`server_id`,`time`,`zoombase`)
);

CREATE TABLE `cpu` (
 `name` varchar(191) NOT NULL,
 `all` bigint(20) NOT NULL,
 `user` bigint(20) NOT NULL,
 `user_niced` bigint(20) NOT NULL,
 `kernel` bigint(20) NOT NULL,
 `io_wait` bigint(20) NOT NULL,
 `idle` bigint(20) NOT NULL,

 `server_id` int(10) unsigned NOT NULL,
 `time` bigint(20) NOT NULL,
 `zoombase` tinyint NOT NULL,

 KEY `server_time` (`server_id`,`time`),
 KEY `server_time_zoom` (`server_id`,`time`,`zoombase`)
);

CREATE TABLE `sql` (
 `bytes_received` bigint(20) NOT NULL,
 `bytes_sent` bigint(20) NOT NULL,
 `handler_commit` bigint(20) NOT NULL,
 `handler_delete` bigint(20) NOT NULL,
 `handler_update` bigint(20) NOT NULL,
 `handler_write` bigint(20) NOT NULL,
 `innodb_data_read` bigint(20) NOT NULL,
 `innodb_data_written` bigint(20) NOT NULL,
 `innodb_data_reads` bigint(20) NOT NULL,
 `innodb_data_writes` bigint(20) NOT NULL,
 `queries` bigint(20) NOT NULL,
 `connections` bigint(20) NOT NULL,

 `innodb_buffer_pool_bytes_data` bigint(20) NOT NULL,
 `innodb_buffer_pool_pages_data` bigint(20) NOT NULL,
 `Innodb_buffer_pool_pages_dirty` bigint(20) NOT NULL,
 `Innodb_buffer_pool_bytes_dirty` bigint(20) NOT NULL,
 `innodb_buffer_pool_pages_free` bigint(20) NOT NULL,
 `innodb_buffer_pool_pages_flushed` bigint(20) NOT NULL,
 `innodb_mem_dictionary` bigint(20) NOT NULL,

 `qcache_free_memory` int(11) NOT NULL,
 `qcache_hits` bigint(20) NOT NULL,
 `qcache_inserts` bigint(20) NOT NULL,
 `qcache_not_cached` bigint(20) NOT NULL,
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
