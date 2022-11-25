<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawal_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->dateTime('time');
            $table->integer('amount');
            $table->string('status');
            $table->string('request_reference');
            $table->string('notes');
            $table->bigInteger('host_id');
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
        Schema::dropIfExists('withdrawal_transactions');
    }
}
