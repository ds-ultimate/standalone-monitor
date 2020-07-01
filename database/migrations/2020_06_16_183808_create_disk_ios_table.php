<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiskIosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disk_ios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->default(1);
            $table->string('diskname');
            $table->bigInteger('read_io');
            $table->bigInteger('read_sector');
            $table->bigInteger('write_io');
            $table->bigInteger('write_sector');
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
        Schema::dropIfExists('disk_ios');
    }
}
