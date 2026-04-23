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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->string('member_id');
            $table->unsignedBigInteger('added_by_id');

            $table->decimal('amount', 15, 2);

            $table->string('action', 100);
            $table->string('type', 50);

            $table->tinyInteger('status')->default(1);

            $table->dateTime('created_at');

           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
