<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cashbook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashbook', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('category');
            $table->string('payer');
            $table->string('method');
            $table->string('ref')->nullable();
            $table->integer('total');
            $table->text('description');
            $table->string('date');
            $table->integer('status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashbook');
    }
}
