<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
{
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('orderid');
        $table->unsignedBigInteger('productid');
        $table->integer('quantity');
        $table->decimal('unitprice', 15, 2);
        $table->timestamps();
        
        // Khóa ngoại
        $table->foreign('orderid')
              ->references('id')
              ->on('orders')
              ->onDelete('cascade');
        
        $table->foreign('productid')
              ->references('id')
              ->on('products')
              ->onDelete('restrict'); // Chặn xóa sản phẩm nếu đã bán
        
        // Indexes
        $table->index('orderid');
        $table->index('productid');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
