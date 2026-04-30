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
        Schema::create('rfb_sellers', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('rfb_id');
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('seller_member_id');
            $table->tinyInteger('status')->nullable()
                ->comment('1=Request Received, 2=Request Accepted, 3=Closed Request');  
            $table->timestamps();
            $table->foreign('rfb_id')->references('id')->on('rfbs');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfb_sellers');
    }
};
