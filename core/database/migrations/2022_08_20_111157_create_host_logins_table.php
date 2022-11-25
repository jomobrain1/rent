<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('host_logins', function (Blueprint $table) {
            $table->id();
            $table->integer('host_id');
            $table->string('host_ip', 40);
            $table->string('city', 40);
            $table->string('country', 40);
            $table->string('country_code', 40);
            $table->string('longitude', 40);
            $table->string('latitude', 40);
            $table->string('browser', 40);
            $table->string('os', 40);
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
        Schema::dropIfExists('host_logins');
    }
}
