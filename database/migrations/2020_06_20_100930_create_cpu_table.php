<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->default(1);
            $table->timestamps();
            $table->string("name");
            $table->bigInteger('all');
            $table->bigInteger('user');
            $table->bigInteger('user_niced');
            $table->bigInteger('kernel');
            $table->bigInteger('io_wait');
            $table->bigInteger('idle');

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
        Schema::dropIfExists('cpu');
    }
}
