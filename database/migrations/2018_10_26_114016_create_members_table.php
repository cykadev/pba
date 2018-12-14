<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businessmen', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bmr_number');
            $table->string('membership');
            $table->string('ntn');
            $table->string('executive_class')->nullable();
            $table->string('name');
            $table->string('gender');
            $table->string('father_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('mobile_number');
            $table->string('mobile_number2')->nullable();
            $table->string('emergency_number');
            $table->string('relation');
            $table->string('email');
            $table->string('address');
            $table->string('postal');
            $table->string('nationality');
            $table->string('cnic')->nullable();
            $table->string('overseas_cnic')->nullable();
            $table->string('overseas_country')->nullable();
            $table->string('dob');
            $table->string('province');
            $table->string('city');
            $table->string('passport_number');
            $table->string('issue_date');
            $table->string('expiry_date');
            $table->string('since')->nullable();
            $table->longText('image');
            $table->integer('status');
            $table->string('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
