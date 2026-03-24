<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::create('inventories', function (Blueprint $table) {
        $table->unsignedBigInteger('ProductId')->primary(); // PK là ProductId
        $table->integer('QuantityInStock')->default(0);
        $table->timestamps();
        
        // Khóa ngoại
        $table->foreign('ProductId')
              ->references('id')
              ->on('products')
              ->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}
