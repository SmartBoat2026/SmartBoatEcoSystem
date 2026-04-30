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
        Schema::table('rfb_sellers', function (Blueprint $table) {
            $table->unsignedBigInteger('sell_history_id')
              ->nullable()
              ->after('seller_member_id');

            // optional foreign key (recommended)
            $table->foreign('sell_history_id')
                ->references('id')
                ->on('sell_wallet_histories')
                ->onDelete('cascade');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rfb_sellers', function (Blueprint $table) {
             $table->dropForeign(['sell_history_id']);
             $table->dropColumn('sell_history_id');
        });
    }
};
