<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');//(1)
            $table->string('transaction_id')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email');
            $table->string('customer_address')->nullable();
            $table->float('sub_total')->unsigned();

            $table->unsignedBigInteger('payment_method_id');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade');

            $table->unsignedBigInteger('delivery_method_id')->default(1);
            $table->foreign('delivery_method_id')->references('id')->on('delivery_methods')->onDelete('cascade');

            $table->string('shipping_fee')->nullable()->default(0);
            $table->float('grand_total')->unsigned();
            $table->integer('payment_status')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

/*
Note`
 */
//(1): must be of type 'bigIncrements' in laravel 5.8, else foreign key of child table will not work.
//Ref: https://stackoverflow.com/a/47728924/11297747
