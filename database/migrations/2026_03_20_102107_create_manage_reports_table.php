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
        Schema::create('manage_reports', function (Blueprint $table) {
            $table->increments('member_id');
            $table->string('memberID', 100);
            $table->string('name', 100);
            $table->string('phone', 100);
            $table->string('password', 100);
            $table->string('email', 100);
            $table->string('sponser_id', 100);

            $table->float('smart_wallet_balance');

            $table->string('joining_date', 100);
            $table->string('transaction_password', 100);

            $table->integer('smart_point');
            $table->string('smart_quanity', 11);

            $table->string('age', 10);
            $table->string('gender', 11);

            $table->string('sponser_name', 255);

            $table->string('status', 10)->comment('1=Active, 2=Pending,3=Rejected');

            $table->string('country_code', 50);
            $table->string('referral_code', 50);
            $table->string('referral_by', 50);

            $table->string('amount', 50);

            $table->text('verification_payment_screenshot');
            $table->string('payment_utr_no', 100);
            $table->text('verification_message');

            $table->string('created_at', 500);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manage_reports');
    }
};
