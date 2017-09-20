<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->comment('姓名');
            $table->string('number')->nullable()->comment('员工编号');
            $table->string('job')->nullable()->comment('职务');
            $table->string('department')->nullable()->comment('部门');
            $table->string('cellphone')->nullable()->comment('手机');
            $table->string('voip')->nullable()->comment('IP电话');
            $table->string('call')->nullable()->comment('语音电话');
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
        Schema::dropIfExists('employees');
    }
}
