<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDetailEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('id_card')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->date('dob')->nullable();
            $table->string('email')->nullable();
            $table->string('line_id')->nullable();
            $table->text('address')->nullable();
            $table->string('position')->default('emp');
            $table->date('start_date')->nullable();
            $table->string('employment_status')->default('1');
            $table->text('note')->nullable();
            $table->double('salary', 11, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'id_card',
                'firstname',
                'lastname',
                'gender',
                'dob',
                'email',
                'line_id',
                'address',
                'position',
                'start_date',
                'employment_status',
                'note',
                'salary',
            ]);
        });
    }
}
