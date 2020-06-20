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
        Schema::create('cpu', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("name");
            $table->bigInteger('all');
            $table->bigInteger('user');
            $table->bigInteger('user_niced');
            $table->bigInteger('kernel');
            $table->bigInteger('io_wait');
            $table->bigInteger('idle');
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
