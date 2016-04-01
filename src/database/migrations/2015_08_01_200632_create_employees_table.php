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
            $table->integer('sub_cost_center_id')->unsigned();
            $table->integer('position_id')->unsigned();
            $table->bigInteger('internal_code')->unique();
            $table->bigInteger('identification_number')->unique();
            $table->string('name');
            $table->string('lastname');
            $table->string('email')->unique()->nullable();
            $table->string('city');
            $table->string('address');
            $table->string('phone');
            $table->enum('status', ['enabled', 'disabled'])->default('enabled');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('sub_cost_center_id')->references('id')->on('sub_cost_centers');
            $table->foreign('position_id')->references('id')->on('positions');
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
