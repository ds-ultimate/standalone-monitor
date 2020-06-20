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
            $table->string('diskname');
            $table->bigInteger('read_io');
            $table->bigInteger('read_sector');
            $table->bigInteger('write_io');
            $table->bigInteger('write_sector');
            $table->timestamps();
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
