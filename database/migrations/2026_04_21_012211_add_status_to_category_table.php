<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('category', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('category', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
