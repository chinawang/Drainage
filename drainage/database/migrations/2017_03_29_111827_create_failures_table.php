<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFailuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('failures', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('equipment_id')->comment('设备id');
            $table->unsignedInteger('station_id')->comment('所属泵站id');
            $table->string('failure_type')->nullable()->comment('故障分类');
            $table->string('failure_description')->nullable()->comment('故障现象描述');
            $table->string('equipment_status')->nullable()->comment('设备状态');
            $table->unsignedInteger('reporter_id')->comment('报修人id');
            $table->dateTime('report_at')->nullable()->comment('报修时间');
            $table->tinyInteger('repair_process')->default(0)->comment('是否完成维修');
            $table->dateTime('repair_at')->nullable()->comment('维修时间');
            $table->unsignedInteger('repairer_id')->comment('维修人id');
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
        Schema::dropIfExists('failures');
    }
}
