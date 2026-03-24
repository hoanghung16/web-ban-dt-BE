<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
        $table->id();
        $table->unsignedBigInteger('categoryid');
        $table->string('name');
        $table->decimal('price', 15, 2);
        $table->decimal('saleprice', 15, 2)->nullable();
        $table->boolean('IsOnSale')->default(false);
        $table->boolean('IsPublished')->default(true);
        $table->string('imageUrl')->nullable();
        $table->timestamps();
        
        // Khóa ngoại
        $table->foreign('categoryid')
              ->references('id')
              ->on('categories')
              ->onDelete('cascade');
        
        // Indexes theo yêu cầu file nhiệm vụ
        $table->index('categoryid');
        $table->index('IsPublished');
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
