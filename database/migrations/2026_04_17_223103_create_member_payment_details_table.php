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
        Schema::create('member_payment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->unique();
            $table->string('account_holder')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('branch_name')->nullable();
            $table->enum('account_type', ['savings','current'])->nullable();
            $table->string('upi_id')->nullable();
            $table->string('upi_mobile')->nullable();
            $table->string('upi_app')->nullable();
            $table->string('qr_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_payment_details');
    }
};
