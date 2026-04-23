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
        Schema::table('member_stp_schedules', function (Blueprint $table) {
            $table->string('running_hrs', 100)->change();
        });
    }

    public function down(): void
    {
        Schema::table('member_stp_schedules', function (Blueprint $table) {
            $table->json('running_hrs')->nullable()->change();
        });
    }
};
