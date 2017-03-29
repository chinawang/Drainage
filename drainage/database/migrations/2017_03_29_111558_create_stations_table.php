<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('station_number')->nullable()->comment('泵站编号');
            $table->string('name')->nullable()->comment('泵站名称');
            $table->string('address')->nullable()->comment('地址');
            $table->string('lat', 20)->nullable()->comment('纬度');
            $table->string('lng', 20)->nullable()->comment('经度');
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
        Schema::dropIfExists('stations');
    }
}
