<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MembershipExpiry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_expiry', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('businessmen_id');
            $table->integer('business_id');
            $table->integer('invoice_id');
            $table->string('paid_date');
            $table->string('expiry_date');
            $table->boolean('status');
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
        Schema::dropIfExists('membership_expiry');
    }
}
