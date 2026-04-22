<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bonus', function (Blueprint $table) {
            $table->id();
            $table->string('bonus_type', 50);
            $table->string('member_id', 50);
            $table->string('total_quantity', 50);
            $table->string('rate', 50);
            $table->decimal('bonus_amount', 10, 2)->default(0.00);
            $table->boolean('status')->default(1);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus');
    }
};
