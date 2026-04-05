<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCloudinaryToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('products', 'cloudinary_public_id')) {
                $table->string('cloudinary_public_id')->nullable()->after('imageUrl');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'cloudinary_public_id')) {
                $table->dropColumn('cloudinary_public_id');
            }
        });
    }
}
