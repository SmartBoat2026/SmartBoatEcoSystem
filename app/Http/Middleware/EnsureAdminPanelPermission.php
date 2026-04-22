<?php

namespace App\Http\Middleware;

use App\Support\AdminPanelAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminPanelPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! AdminPanelAccess::isStaff()) {
            return $next($request);
        }

        $name = $request->route()?->getName();

        $map = config('admin_panel_permissions.route_permissions', []);

        if ($name === null || ! array_key_exists($name, $map)) {
            abort(403, 'This action is not permitted for staff accounts.');
        }

        $required = $map[$name];
        $keys = is_array($required) ? $required : [$required];

        if (! AdminPanelAccess::canAny($keys)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            return redirect()
                ->route('admin.no-access')
                ->with('error', 'You do not have permission to access that section.');
        }

        return $next($request);
    }
}
