<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('station_id')->comment('所属泵站id');
            $table->string('equipment_number')->nullable()->comment('设备编号');
            $table->string('name')->nullable()->comment('设备名称');
            $table->string('type')->nullable()->comment('型号');
            $table->string('producer')->nullable()->comment('制造单位');
            $table->string('department')->nullable()->comment('使用部门');
            $table->unsignedInteger('leader_id')->nullable()->comment('负责人id');
            //$table->string('leader')->nullable()->comment('负责人');
            $table->unsignedInteger('custodian_id')->nullable()->comment('设备管理员id');
            //$table->string('custodian')->nullable()->comment('设备管理员');
            $table->unsignedMediumInteger('quantity')->default(0)->comment('数量');
            $table->text('alteration')->nullable()->comment('变更情况描述');
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
        Schema::dropIfExists('equipments');
    }
}
