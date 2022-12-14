<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees_laptops', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true)->comment( 'Auto increment');
            $table->unsignedBigInteger('laptop_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedTinyInteger('approved_status')->default(3)->nullable()->comment( '1: rejected, 2: approved, 3: pending for approval, 4: pending for update approval');
            $table->unsignedTinyInteger('brought_home_flag')->default(0)->nullable()->comment( '0: PC is used inside the office only, 1: PC is also used outside the office');
            $table->unsignedTinyInteger('vpn_flag')->default(0)->nullable()->comment( '0: no access 1: has access');
            $table->unsignedTinyInteger('surrender_flag')->default(0);
            $table->dateTime('surrender_date')->nullable();
            $table->string('remarks', 1024)->nullable();
            $table->string('reasons', 1024)->nullable();
            $table->json('update_data')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->dateTime('create_time')->nullable();
            $table->dateTime('update_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees_laptops');
    }
};
