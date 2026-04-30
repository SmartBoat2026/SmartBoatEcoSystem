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
        Schema::create('sell_wallet_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->decimal('show_wallet_balance', 12, 2);
            $table->decimal('total_sell_wallet_balance', 12, 2);
            $table->tinyInteger('payment_method')->nullable()
                ->comment('1=UPI Transfer via QR Code, 2=UPI Number, 3=Bank to Bank Transfer,4=Cash to Bank Transfer');
            $table->string('mobile_number')->nullable();
            $table->tinyInteger('status')
                ->default(1)
                ->comment('1=Active, 2=Closed, 3=cancelled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sell_wallet_histories');
    }
};
