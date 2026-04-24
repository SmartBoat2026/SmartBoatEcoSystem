<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class DirectBonusCalculator
{
    public const SETTING_KEY_DIRECT_BONUS = 'direct_bonus';

    /**
     * Raw JSON from site_settings.setting_value where setting_key = direct_bonus.
     */
    public static function rawDirectBonusSetting(): ?string
    {
        try {
            $raw = DB::table('site_settings')
                ->whereRaw('LOWER(setting_key) = ?', [strtolower(self::SETTING_KEY_DIRECT_BONUS)])
                ->value('setting_value');
        } catch (\Throwable) {
            return null;
        }

        if (! is_string($raw)) {
            return null;
        }

        $raw = preg_replace('/^\xEF\xBB\xBF/', '', trim($raw));

        return $raw === '' ? null : $raw;
    }

    /**
     * Decode JSON from site_settings (handles double-encoded JSON string).
     *
     * @return array<string, mixed>|list<mixed>|null
     */
    public static function decodeSettingJson(string $raw): ?array
    {
        $decoded = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }
        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);
            if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
                return null;
            }
        }
        if (! is_array($decoded)) {
            return null;
        }

        return $decoded;
    }

    /**
     * Slabs from site_settings only. Invalid / missing config => empty array (no bonus).
     *
     * @return array<int, array{min: float, max: float|null, percent: float}>
     */
    public static function slabsFromSettings(): array
    {
        $raw = self::rawDirectBonusSetting();
        if ($raw === null) {
            return [];
        }

        $decoded = self::decodeSettingJson($raw);
        if ($decoded === null) {
            return [];
        }

        $slabs = $decoded['slabs'] ?? $decoded;
        if (! is_array($slabs) || $slabs === []) {
            return [];
        }

        $normalized = [];
        foreach ($slabs as $row) {
            if (! is_array($row) || ! isset($row['min'], $row['percent'])) {
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
            return [];
        }

        usort($normalized, static fn (array $a, array $b): int => $a['min'] <=> $b['min']);

        return $normalized;
    }

    /**
     * Percent for purchase amount (INR), 0 if no slab matches or no slabs configured.
     */
    public static function percentForAmount(float $amount): float
    {
        if ($amount <= 0) {
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
