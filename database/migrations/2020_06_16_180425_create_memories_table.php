<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->default(1);
            $table->bigInteger('mem_total');
            $table->bigInteger('file_cache_size');
            $table->bigInteger('used_programms');
            $table->bigInteger('used_buffers');
            $table->bigInteger('used_cache');
            $table->bigInteger('free');
            $table->timestamps();

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
        Schema::dropIfExists('memories');
    }
}
