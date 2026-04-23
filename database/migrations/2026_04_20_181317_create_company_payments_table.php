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
        Schema::create('smart_wallet_company_payments', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('sender_member_id');
            $table->unsignedBigInteger('admin_member_id')->nullable();

            $table->decimal('amount', 12, 2);

            $table->string('qr_file')->nullable();
            $table->string('transaction_id')->nullable();

            $table->tinyInteger('status')
                ->default(1)
                ->comment('1=pending, 2=active, 3=rejected');

            $table->text('comment')->nullable();

            $table->timestamps();

            // $table->foreign('sender_member_id')
            //     ->references('member_id')
            //     ->on('manage_reports')
            //     ->onDelete('cascade');

            // $table->foreign('admin_member_id')
            //     ->references('member_id')
            //     ->on('manage_reports')
            //     ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_payments');
    }
};
