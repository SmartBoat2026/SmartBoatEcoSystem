<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class DirectBonusCalculator
{
    /**
     * @return array<int, array{min: float|int, max: float|int|null, percent: float|int}>
     */
    public static function defaultSlabs(): array
    {
        return [
            ['min' => 1, 'max' => 999, 'percent' => 3],
            ['min' => 1000, 'max' => 5999, 'percent' => 5],
            ['min' => 6000, 'max' => 9999, 'percent' => 7],
            ['min' => 10000, 'max' => null, 'percent' => 10],
        ];
    }

    /**
     * Load slabs from site_settings.direct_bonus JSON, or defaults.
     *
     * @return array<int, array{min: float, max: float|null, percent: float}>
     */
    public static function slabsFromSettings(): array
    {
        $raw = DB::table('site_settings')->where('setting_key', 'direct_bonus')->value('setting_value');
        if (!$raw) {
            return self::defaultSlabs();
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return self::defaultSlabs();
        }

        $slabs = $decoded['slabs'] ?? $decoded;
        if (!is_array($slabs) || $slabs === []) {
            return self::defaultSlabs();
        }

        $normalized = [];
        foreach ($slabs as $row) {
            if (!is_array($row) || !isset($row['min'], $row['percent'])) {
                continue;
            }
            $max = array_key_exists('max', $row) ? $row['max'] : null;
            $normalized[] = [
                'min'     => (float) $row['min'],
                'max'     => $max === null || $max === '' ? null : (float) $max,
                'percent' => (float) $row['percent'],
            ];
        }

        if ($normalized === []) {
            return self::defaultSlabs();
        }

        usort($normalized, static fn (array $a, array $b): int => $a['min'] <=> $b['min']);

        return $normalized;
    }

    /**
     * Percent for purchase amount (INR), 0 if no slab matches.
     */
    public static function percentForAmount(float $amount): float
    {
        if ($amount < 1) {
            return 0.0;
        }

        foreach (self::slabsFromSettings() as $slab) {
            $min = $slab['min'];
            $max = $slab['max'];
            if ($amount < $min) {
                continue;
            }
            if ($max === null || $amount <= $max) {
                return (float) $slab['percent'];
            }
        }

        return 0.0;
    }

    public static function bonusAmount(float $purchaseTotal): float
    {
        $pct = self::percentForAmount($purchaseTotal);
        if ($pct <= 0) {
            return 0.0;
        }

        return round($purchaseTotal * ($pct / 100), 2);
    }
}
