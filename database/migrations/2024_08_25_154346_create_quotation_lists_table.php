<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quotations_id');
            $table->string('q_name')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->float('price',11,2)->nullable();
            $table->timestamps();

            $table->foreign('quotations_id')->references('id')->on('quotations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotation_lists');
    }
}
