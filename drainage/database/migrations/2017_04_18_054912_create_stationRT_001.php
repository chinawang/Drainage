<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStationRT001 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stationRT_001', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Time')->nullable()->comment('时间');
            $table->string('TagName')->nullable()->comment('监测点名称');
            $table->float('PV')->nullable()->comment('检测值');
            $table->string('DESC')->nullable()->comment('描述');
//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stationRT_001');
    }
}
