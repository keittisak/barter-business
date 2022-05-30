<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnPointBalanceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_point_balance_details', function (Blueprint $table) {
            $table->id();
            $table->integer('document_id')->unsigned();
            $table->integer('balance_id')->unsigned();
            $table->integer('point_id')->unsigned();
            $table->double('before_total_amount', 10,2);
            $table->double('after_total_amount', 10,2);
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('return_point_balance_details');
    }
}
