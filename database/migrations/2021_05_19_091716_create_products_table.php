<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('number');
            $table->string('productName');
            $table->string('picture');
            $table->unsignedBigInteger('series_id');
            $table->string('fullNumber');

            $table->foreign('series_id')
            ->references('id')
            ->on('series')
            ->onDelete('cascade');
            
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
        Schema::dropIfExists('products');
    }
}
