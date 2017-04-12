<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaintenancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('failure_id')->nullable()->comment('报修单id');
            $table->unsignedInteger('equipment_id')->comment('设备id');
            $table->unsignedInteger('station_id')->comment('所属泵站id');
            $table->unsignedInteger('repairer_id')->nullable()->comment('维修人id');
            $table->string('failure_reason')->nullable()->comment('故障原因');
            $table->string('repair_solution')->nullable()->comment('维修解决办法');
            $table->dateTime('repair_at')->nullable()->comment('维修时间');
            $table->tinyInteger('repair_process')->default(0)->comment('是否完成维修');
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
        Schema::dropIfExists('maintenances');
    }
}
