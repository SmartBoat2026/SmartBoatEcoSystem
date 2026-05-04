<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Key/value settings (e.g. direct_bonus slabs as JSON).
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key', 191)->unique();
            $table->longText('setting_value');
            $table->timestamps();
        });

        $defaultDirectBonus = [
            'slabs' => [
                ['min' => 1,    'max' => 999,    'percent' => 3],
                ['min' => 1000, 'max' => 5000,   'percent' => 5],
                ['min' => 5001, 'max' => 5999,   'percent' => 5],
                ['min' => 6000, 'max' => 10000,  'percent' => 7],
                ['min' => 10001, 'max' => 10999, 'percent' => 7],
                ['min' => 11000, 'max' => null,  'percent' => 10],
            ],
        ];

        DB::table('site_settings')->insert([
            'setting_key'   => 'direct_bonus',
            'setting_value' => json_encode($defaultDirectBonus, JSON_UNESCAPED_UNICODE),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
