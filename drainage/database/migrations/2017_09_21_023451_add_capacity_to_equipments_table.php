<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCapacityToEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->string('capacity')->nullable()->comment('容量');
            $table->string('flux')->nullable()->comment('流量');
            $table->string('range')->nullable()->comment('扬程');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->dropColumn('capacity');
            $table->dropColumn('flux');
            $table->dropColumn('range');
        });
    }
}
