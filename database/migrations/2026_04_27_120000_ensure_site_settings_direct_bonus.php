<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('site_settings')) {
            Schema::create('site_settings', function (Blueprint $table) {
                $table->id();
                $table->string('setting_key', 191)->unique();
                $table->longText('setting_value');
                $table->timestamps();
            });
        }

        $key     = 'direct_bonus';
        $payload = [
            'slabs' => [
                ['min' => 1, 'max' => 999, 'percent' => 3],
                ['min' => 1000, 'max' => 5000, 'percent' => 5],
                ['min' => 5001, 'max' => 5999, 'percent' => 5],
                ['min' => 6000, 'max' => 10000, 'percent' => 7],
                ['min' => 10001, 'max' => 10999, 'percent' => 7],
                ['min' => 11000, 'max' => null, 'percent' => 10],
            ],
        ];
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE);

        $row = DB::table('site_settings')
            ->whereRaw('LOWER(setting_key) = ?', [strtolower($key)])
            ->first();

        if ($row === null) {
            DB::table('site_settings')->insert([
                'setting_key'   => $key,
                'setting_value' => $json,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            return;
        }

        $current = trim((string) ($row->setting_value ?? ''));
        if ($current === '') {
            DB::table('site_settings')
                ->where('id', $row->id)
                ->update([
                    'setting_value' => $json,
                    'updated_at'    => now(),
                ]);
        }
    }

    public function down(): void
    {
        // Intentionally left blank: do not remove site_settings or direct_bonus in rollback.
    }
};
