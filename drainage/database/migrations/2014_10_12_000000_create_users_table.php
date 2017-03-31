<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100)->unique();
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('employee_number')->nullable()->comment('员工编号');
            $table->string('realname')->nullable()->comment('姓名');
            $table->string('office')->nullable()->comment('职位');
            $table->string('contact')->nullable()->comment('联系方式');
            $table->tinyInteger('delete_process')->default(0)->comment('是否删除');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
