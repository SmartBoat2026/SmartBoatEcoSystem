<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->string('hsn_code')->nullable()->after('name');
            $table->decimal('base_price', 10, 2)->default(0)->after('smart_points');
        });
    }

    public function down(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['hsn_code', 'base_price']);
        });
    }
};
