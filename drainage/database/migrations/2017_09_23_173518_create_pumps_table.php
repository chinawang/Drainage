<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePumpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pumps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('station_id')->comment('所属泵站id');
            $table->float('flux1',8,2)->default(0)->comment('1号泵抽数量m³/h');
            $table->float('flux2',8,2)->default(0)->comment('2号泵抽数量m³/h');
            $table->float('flux3',8,2)->default(0)->comment('3号泵抽数量m³/h');
            $table->float('flux4',8,2)->default(0)->comment('4号泵抽数量m³/h');
            $table->float('flux5',8,2)->default(0)->comment('5号泵抽数量m³/h');
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
        Schema::dropIfExists('pumps');
    }
}
