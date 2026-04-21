<?php
// database/migrations/2026_04_02_000002_create_product_purchaselists_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_purchaselists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->string('member_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->string('product_hsn')->nullable();
            $table->decimal('product_baseprice', 15, 2)->default(0);
            $table->decimal('product_dp', 15, 2)->default(0);
            $table->decimal('product_count', 15, 4)->default(0);
            $table->decimal('product_smartpoints', 15, 4)->default(0);
            $table->decimal('product_smartqty', 15, 4)->default(0);
            $table->decimal('product_total', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('purchase_id')
                  ->references('id')->on('product_purchases')
                  ->onDelete('cascade');

            $table->foreign('product_id')
                  ->references('id')->on('products')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_purchaselists');
    }
};