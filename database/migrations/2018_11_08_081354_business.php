<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Business extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business', function (Blueprint $table) {
            $table->increments('id');
            $table->string('br_number');
            $table->string('name');
            $table->string('designation');
            $table->string('address');
            $table->string('office');
            $table->string('website')->nullable();
            $table->string('product');
            $table->string('gst')->nullable();
            $table->string('category');
            $table->string('criteria')->nullable();
            $table->string('aop')->nullable();
            $table->integer('status');
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
        Schema::dropIfExists('business');
    }
}
