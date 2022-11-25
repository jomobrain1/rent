<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->integer('plan_id')->default(0);
            $table->integer('pick_location')->default(0);
            $table->timestamp('pick_time')->nullable();
            $table->decimal('price', 18,8)->default(0);
            $table->string('trx')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('plan_logs');
    }
}
