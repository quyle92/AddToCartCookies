<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('product_fullNumber')->unique();
            $table->foreign('product_fullNumber')->references('fullNumber')->on('products')->onDelete('cascade');

            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            
            $table->float('unit_price')->unsigned();
            $table->integer('order_quantity')->unsigned();
            $table->integer('discount')->nullable()->default(NULL);
            $table->float('order_amount')->unsigned();
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
        Schema::dropIfExists('order_details');
    }
}
