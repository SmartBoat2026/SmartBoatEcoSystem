<?php
// database/migrations/xxxx_xx_xx_create_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->string('name');
            $table->decimal('smart_points', 10, 2)->default(0);
            $table->decimal('distribution_price', 10, 2)->default(0);
            $table->decimal('qty', 10, 4)->default(0); // auto-calculated
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');
            $table->foreign('subcategory_id')->references('id')->on('category')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('products');
    }
};