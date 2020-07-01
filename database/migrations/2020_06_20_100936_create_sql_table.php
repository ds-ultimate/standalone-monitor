<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSqlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sqls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->default(1);
            $table->timestamps();
            $table->bigInteger('bytes_received');
            $table->bigInteger('bytes_sent');
            $table->bigInteger('handler_commit');
            $table->bigInteger('handler_delete');
            $table->bigInteger('handler_update');
            $table->bigInteger('handler_write');
            $table->bigInteger('innodb_data_read');
            $table->bigInteger('innodb_data_written');
            $table->bigInteger('innodb_data_reads');
            $table->bigInteger('innodb_data_writes');
            $table->bigInteger('queries');;
            $table->bigInteger('connections');

            $table->integer('innodb_buffer_pool_bytes_data');
            $table->integer('innodb_buffer_pool_pages_data');
            $table->integer('innodb_buffer_pool_pages_free');
            $table->integer('innodb_buffer_pool_pages_flushed');
            $table->integer('innodb_mem_dictionary');
            $table->integer('innodb_mem_total');

            $table->integer('qcache_free_memory');
            $table->bigInteger('qcache_hits');
            $table->bigInteger('qcache_inserts');
            $table->bigInteger('qcache_not_cached');
            $table->integer('qcache_total_blocks');

            $table->foreign('server_id')->references('id')->on('servers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sql');
    }
}
