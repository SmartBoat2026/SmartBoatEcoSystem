<?php

namespace App\Support;

class AdminPanelAccess
{
    public static function isStaff(): bool
    {
        return (bool) session('admin_is_staff', false);
    }

    public static function permissions(): array
    {
        return session('admin_permissions', []) ?: [];
    }

    public static function can(string $permission): bool
    {
        if (! session('admin_logged_in')) {
            return false;
        }
        if (! self::isStaff()) {
            return true;
        }

        return in_array($permission, self::permissions(), true);
    }

    /** @param  list<string>  $permissions */
    public static function canAny(array $permissions): bool
    {
        foreach ($permissions as $p) {
            if (self::can($p)) {
                return true;
            }
        }

        return false;
    }

    public static function defaultRedirectUrl(): string
    {
        if (! self::isStaff()) {
            return route('admin.index');
        }

        $order = config('admin_panel_permissions.staff_login_redirect_order', []);

        foreach ($order as $perm => $routeName) {
            if (self::can($perm) && $routeName) {
                return route($routeName);
            }
        }

        return route('admin.no-access');
    }

    public static function actorId(): int
    {
        if (session('staff_id')) {
            return (int) session('staff_id');
        }

        return (int) (session('admin_id') ?? 1);
    }
}
