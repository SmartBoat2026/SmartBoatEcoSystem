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
        Schema::create('rfbs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->string('rfb_id');
            $table->decimal('amount', 10, 2);
            $table->unsignedInteger('no_of_sellers')
              ->default(0);
            $table->tinyInteger('status')->nullable()
                ->comment('1=Request Sent, 2=Request Accepted, 3=Closed Request');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfbs');
    }
};
