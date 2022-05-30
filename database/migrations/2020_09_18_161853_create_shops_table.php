<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->increments('id');
            // $table->string('code', 30)->uniqid();
            $table->string('name')->nullable();
            $table->integer('user_id')->unique();
            $table->integer('type_id')->unsigned();
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->text('address')->nullable();
            $table->integer('country_id')->unsigned()->nullable();
            $table->integer('province_id')->unsigned()->nullable();
            $table->integer('district_id')->unsigned()->nullable();
            $table->integer('subdistrict_id')->unsigned()->nullable();
            $table->string('postalcode', 5)->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('full_address')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('line_id')->nullable();
            $table->string('facebook_id')->nullable();
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
        Schema::dropIfExists('shops');
    }
}
