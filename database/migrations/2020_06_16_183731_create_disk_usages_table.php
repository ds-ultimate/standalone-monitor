<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiskUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disk_usages', function (Blueprint $table) {
            $table->id();
            $table->string('diskname');
            $table->string('mounted_at');
            $table->bigInteger('kbytes_all');
            $table->bigInteger('kbytes_used');
            $table->bigInteger('kbytes_reserved');
            $table->bigInteger('inodes_all');
            $table->bigInteger('inodes_used');
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
        Schema::dropIfExists('disk_usages');
    }
}
