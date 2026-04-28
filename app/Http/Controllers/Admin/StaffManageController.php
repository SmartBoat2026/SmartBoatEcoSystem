<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\PanelStaff;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StaffManageController extends Controller
{
    public function index()
    {
        $staff = PanelStaff::query()->orderByDesc('id')->get();

        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        $modules = config('admin_panel_permissions.modules', []);

        return view('admin.staff.form', [
            'staff' => null,
            'modules' => $modules,
            'selected' => [],
        ]);
    }

    public function store(Request $request)
    {
        $modules = array_keys(config('admin_panel_permissions.modules', []));

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required', 'string', 'max:190',
                Rule::unique('panel_staff', 'username'),
                function ($attribute, $value, $fail) {
                    if (Admin::where('username', $value)->exists()) {
                        $fail('This username is already used by an admin account.');
                    }
                },
            ],
            'password' => 'required|string|min:4|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|in:'.implode(',', $modules),
        ]);

        $perms = array_values(array_intersect($modules, $validated['permissions'] ?? []));

        if ($perms === []) {
            return back()->withInput()->withErrors(['permissions' => 'Select at least one feature permission.']);
        }

        PanelStaff::query()->create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => $validated['password'],
            'permissions' => $perms,
            'is_active' => true,
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member created successfully.');
    }

    public function edit(int $id)
    {
        $staff = PanelStaff::query()->findOrFail($id);
        $modules = config('admin_panel_permissions.modules', []);
        $selected = $staff->permissions ?? [];

        return view('admin.staff.form', compact('staff', 'modules', 'selected'));
    }

    public function update(Request $request, int $id)
    {
        $staff = PanelStaff::query()->findOrFail($id);
        $modules = array_keys(config('admin_panel_permissions.modules', []));

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required', 'string', 'max:190',
                Rule::unique('panel_staff', 'username')->ignore($staff->id),
                function ($attribute, $value, $fail) {
                    if (Admin::where('username', $value)->exists()) {
                        $fail('This username is already used by an admin account.');
                    }
                },
            ],
            'password' => 'nullable|string|min:4|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|in:'.implode(',', $modules),
            'is_active' => 'nullable|in:0,1',
        ]);

        $perms = array_values(array_intersect($modules, $validated['permissions'] ?? []));

        if ($perms === []) {
            return back()->withInput()->withErrors(['permissions' => 'Select at least one feature permission.']);
        }

        $staff->name = $validated['name'];
        $staff->username = $validated['username'];
        if (! empty($validated['password'])) {
            $staff->password = $validated['password'];
        }
        $staff->permissions = $perms;
        $staff->is_active = (bool) (int) $request->input('is_active', 0);
        $staff->save();

        return redirect()->route('admin.staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function destroy(int $id)
    {
        $staff = PanelStaff::query()->findOrFail($id);
        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff member removed.');
    }
}
