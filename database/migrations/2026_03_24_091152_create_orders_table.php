<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
        $table->id();
        $table->unsignedBigInteger('userid');
        $table->timestamp('orderdate')->useCurrent();
        $table->string('status')->default('Pending'); 
        $table->string('paymentstatus')->default('Unpaid');
        $table->decimal('totalprice', 15, 2);
        $table->string('shipname');
        $table->text('shipaddress');
        $table->string('shipphone');
        $table->timestamps();
        
        // Khóa ngoại
        $table->foreign('userid')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');
        
        // Indexes
        $table->index('userid');
        $table->index('status');
        $table->index('paymentstatus');
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
