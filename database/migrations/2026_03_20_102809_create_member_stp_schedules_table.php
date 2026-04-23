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
        Schema::create('member_stp_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('added_by_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->json('running_hrs')->nullable();
            $table->decimal('per_hrs_amount', 10, 2)->default(0);
            $table->decimal('per_day_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_stp_schedules');
    }
};
