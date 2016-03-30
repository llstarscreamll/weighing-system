<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('internal_code')->unique();
            $table->bigInteger('identification_number')->unique();
            $table->string('name');
            $table->string('lastname');
            $table->enum('status', ['enabled', 'disabled'])->default('enabled');
            $table->string('email')->unique()->nullable();
            $table->string('city');
            $table->string('address');
            $table->string('phone');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('employees');
    }
}
