<?php
// database/migrations/2026_04_02_000001_create_product_purchases_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('member_id')->nullable();
            $table->string('invoice_no')->unique();
            $table->date('purchase_date');
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('total_smartpoint', 15, 4)->default(0);
            $table->decimal('total_smartquantity', 15, 4)->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_purchases');
    }
};