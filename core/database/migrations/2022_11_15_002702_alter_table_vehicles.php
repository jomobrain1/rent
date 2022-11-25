<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableVehicles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('year');
            $table->string('minDistance');
            $table->string('minDays');
            $table->string('cc');
            $table->string('navigation');
            $table->string('pickup');
            $table->string('pickoff');
            $table->json('picks')->nullable();
            $table->json('areas')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('year');
            $table->dropColumn('minDistance');
            $table->dropColumn('minDays');
            $table->dropColumn('cc');
            $table->dropColumn('navigation');
            $table->dropColumn('pickup');
            $table->dropColumn('pickoff');
            $table->dropColumn('picks');
            $table->dropColumn('areas');
   
        });
    }
}
