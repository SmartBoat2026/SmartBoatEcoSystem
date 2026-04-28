<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rfbs', function (Blueprint $table) {
            $table->tinyInteger('status')
                ->nullable()
                ->comment('1=Request Sent, 2=Request Accepted, 3=Closed Request,4=Closed Sell, 5=Buyer Payment Transferred, 6=Seller Payment Received')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('rfbs', function (Blueprint $table) {
            $table->tinyInteger('status')
                ->nullable()
                ->comment('1=Request Sent, 2=Request Accepted, 3=Closed Request')
                ->change();
        });
    }
};
