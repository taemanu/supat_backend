<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employees_id');
            $table->date('month')->nullable();
            $table->integer('ot')->nullable();
            $table->integer('stop_work')->nullable();
            $table->float('withdraw_advance',11,2)->nullable();
            $table->timestamps();

            $table->foreign('employees_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slaries');
    }
}
