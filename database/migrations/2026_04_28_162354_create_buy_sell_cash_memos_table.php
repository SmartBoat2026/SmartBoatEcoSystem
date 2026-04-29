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
        Schema::create('buy_sell_cash_memos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_member_id');
            $table->unsignedBigInteger('receiver_member_id')->nullable();
            $table->unsignedBigInteger('rfb_id')->nullable();
            $table->unsignedBigInteger('sell_history_id')->nullable();

            $table->decimal('amount', 12, 2);

            $table->string('qr_file')->nullable();
            $table->string('transaction_id')->nullable();

            $table->tinyInteger('status')
                ->default(1)
                ->comment('1=pending, 2=accept, 3=rejected');

            $table->text('comment')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buy_sell_cash_memos');
    }
};
