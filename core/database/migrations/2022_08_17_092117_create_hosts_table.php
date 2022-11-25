<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('image');
            $table->string('password');
            $table->string('country_code', 40);
            $table->decimal('balance', 5, 2)->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('ev')->default(0);
            $table->tinyInteger('sv')->default(0);
            $table->string('ver_code', 40);
            $table->dateTime('ver_code_send_at');
            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('hosts');
    }
}
