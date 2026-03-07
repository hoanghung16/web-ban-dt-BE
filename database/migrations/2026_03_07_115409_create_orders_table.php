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
            $table->foreignId('userid')->constrained('users', 'id')->onDelete('cascade');
            $table->dateTime('orderdate')->useCurrent();
            $table->string('status', 50)->default('Pending');
            $table->string('paymentstatus', 50)->default('Unpaid');
            $table->decimal('totalprice', 15, 2);
            $table->string('shipname')->nullable();
            $table->text('shipaddress')->nullable();
            $table->string('shipphone', 20)->nullable();
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
        Schema::dropIfExists('orders');
    }
}
