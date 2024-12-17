<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatedProjectGarageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_garage_customer', function (Blueprint $table) {
            $table->id();

            $table->string('type')->default('master_garage');

            $table->string('project_id')->nullable();
            $table->string('project_name')->nullable();
            $table->string('project_code')->nullable();
            $table->string('customer_code')->nullable();


            $table->string('garage_steel_type')->comment('ชนิดเหล็ก เช่น เหล็ก 1, เหล็ก 2');
            $table->string('garage_steel_thickness', 5, 2)->comment('ความหนาเหล็ก เช่น 0.2');
            $table->string('garage_steel_color')->nullable(); // สีเหล็ก
            $table->string('garage_sheet_type')->comment(' ประเภทแผ่นหลังคา เช่น แผ่นหลังคา 1, 2, 3');//
            $table->string('garage_sheet_color')->nullable(); // สีหลังคา
            $table->text('garage_note')->nullable(); // หมายเหตุ

            $table->string('status')->nullable();


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
        Schema::dropIfExists('project_garage');
    }
}
