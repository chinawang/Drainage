<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStationRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('station_records', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('station_num')->comment('泵站编号');
            $table->unsignedInteger('pump_num')->comment('水泵编号');
            $table->dateTime('run_at')->nullable()->comment('运行时期');
            $table->dateTime('start_at')->nullable()->comment('起泵时间');
            $table->dateTime('stop_at')->nullable()->comment('停泵时间');
            $table->float('start_value')->nullable()->comment('起泵水位');
            $table->float('stop_value')->nullable()->comment('停泵水位');
            $table->integer('run_time')->nullable()->comment('本次运行时间');
            $table->integer('run_current')->nullable()->comment('运行电流');
            $table->tinyInteger('delete_process')->default(0)->comment('是否删除');
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
        Schema::dropIfExists('station_records');
    }
}
