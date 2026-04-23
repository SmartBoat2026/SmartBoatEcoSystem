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
        Schema::create('smart_wallet_user_to_users', function (Blueprint $table) {
            $table->id();

            $table->string('sender_member_id');
            $table->string('receiver_member_id');

            $table->decimal('wallet_balance', 12, 2);
            $table->decimal('request_balance', 12, 2);

            $table->tinyInteger('status')
                ->default(1)
                ->comment('1 = pending, 2 = accepted, 3 = rejected, 4 = cancelled ,5= confirmed, 6=received ');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smart_wallet_requests');
    }
};
