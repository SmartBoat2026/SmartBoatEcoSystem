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
        Schema::table('sell_wallet_histories', function (Blueprint $table) {            
            $table->string('qr_image')->nullable()->after('mobile_number');
            $table->text('payment_details')->nullable()->after('qr_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sell_wallet_histories', function (Blueprint $table) {
            $table->dropColumn(['qr_image', 'payment_details']);
        });
    }
};
