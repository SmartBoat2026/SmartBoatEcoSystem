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
            $table->tinyInteger('status')
                ->nullable()
                ->comment('1=Request Received, 2=Request Accepted, 3=Closed Request, 4=Closed Sell')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rfb_sellers', function (Blueprint $table) {
            $table->tinyInteger('status')
                ->nullable()
                ->comment('1=Request Received, 2=Request Accepted, 3=Closed Request')
                ->change();
        });
    }
};
