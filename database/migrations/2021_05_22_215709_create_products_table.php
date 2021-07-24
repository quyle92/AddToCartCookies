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
            $table->string('fullNumber');

            $table->unsignedBigInteger('series_id');//color_id size_id style_id
            $table->foreign('series_id')->references('id')->on('series')->onDelete('cascade');

            $table->unsignedBigInteger('style_id');//color_id size_id style_id
            $table->foreign('style_id')->references('id')->on('styles')->onDelete('cascade');

            $table->unsignedBigInteger('size_id');//color_id size_id style_id
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');

            $table->unsignedBigInteger('color_id');//color_id size_id style_id
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');

            $table->string('picture');
            $table->decimal('price')->default(0.00);

            $table->integer('quantity')->default(0);

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

/*
Note for migration
 */
//-migration file of parent table must be generrated first .
//-datatype of primimary key in parent table must match that of FK in child table
//-table name at function down() must match that of up() function, Foreign key constrain error will be thrown out when rollback or reset migration.
