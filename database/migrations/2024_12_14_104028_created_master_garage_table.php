<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatedMasterGarageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_garage', function (Blueprint $table) {
            $table->id();

            $table->string('project_name')->nullable();
            $table->string('project_code')->nullable();

            $table->decimal('min_price',10,2)->nullable();
            $table->decimal('max_price',10,2)->nullable();

            $table->text('img')->nullable();

            $table->json('type_steel')->nullable();
            $table->json('thickness_steel')->nullable();
            $table->json('type_sheet')->nullable();


            $table->text('note')->nullable(); // หมายเหตุ

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
        Schema::dropIfExists('master_garage');
    }
}
