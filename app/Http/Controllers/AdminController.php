<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\ManageReport;
use App\Models\PanelStaff;
use App\Support\AdminPanelAccess;

class AdminController extends Controller
{
    public function login()
    {
        if (session()->has('admin_logged_in'))  return redirect('/admin-page');
        if (session()->has('member_logged_in')) return redirect('/member/dashboard');
        return view('admin.login');
    }

    public function doLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = trim($request->username);
        $password = trim($request->password);

        // 1. Try Admin
        $admin = Admin::where('username', $username)->first();
        if ($admin && $admin->verifyPassword($password)) {
            session([
                'admin_logged_in' => true,
                'admin_id' => $admin->admin_id,
                'admin_name' => $admin->name,
                'admin_username' => $admin->username,
                'admin_role' => $admin->role,
                'type' => 'Admin',
                'admin_is_staff' => false,
                'staff_id' => null,
                'admin_permissions' => [],
            ]);

            return redirect('/admin-page')->with('success', 'Welcome back, '.$admin->name.'!');
        }

        $staff = PanelStaff::query()
            ->where('username', $username)
            ->where('is_active', true)
            ->first();

        if ($staff && $staff->verifyPassword($password)) {
            $perms = array_values(array_filter($staff->permissions ?? []));
            if ($perms === []) {
                return back()->withErrors([
                    'username' => 'This staff account has no permissions assigned. Contact administrator.',
                ])->withInput(['username' => $username]);
            }

            session([
                'admin_logged_in' => true,
                'admin_id' => null,
                'staff_id' => $staff->id,
                'admin_name' => $staff->name,
                'admin_username' => $staff->username,
                'admin_role' => 'staff',
                'type' => 'Admin',
                'admin_is_staff' => true,
                'admin_permissions' => $perms,
            ]);

            return redirect(AdminPanelAccess::defaultRedirectUrl())
                ->with('success', 'Welcome, '.$staff->name.'!');
        }

        // 3. Try Member
        $member = ManageReport::where('memberID', $username)
                    ->orWhere('name', $username)->first();

        if ($member && $member->verifyPassword($password)) {
            if ($member->status == 3) {
                return back()->withErrors([
                    'username' => 'Your account has been blocked. Please contact support.'
                ])->withInput(['username' => $username]);
            }
            session([
                'member_logged_in' => true,
                'member_id'        => $member->member_id,
                'member_memberID'  => $member->memberID,
                'member_name'      => $member->name,
                'member_email'     => $member->email,
                'member_phone'     => $member->phone,
                'member_smart_pts' => $member->smart_point,
                'member_smart_qty' => $member->smart_quanity,
                'member_sponsor'   => $member->sponser_id,
                'type'             => 'Member',
                'status'           => $member->status,
            ]);
            return redirect('/member/dashboard')->with('success', 'Welcome, ' . $member->name . '!');
        }

        // 4. Neither matched
        return back()->withErrors([
            'username' => 'Invalid Member ID / Username or Password.',
        ])->withInput(['username' => $username]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget([
            'admin_logged_in', 'admin_id', 'admin_name',
            'admin_username', 'admin_role', 'admin_is_staff',
            'staff_id', 'admin_permissions', 'type',
        ]);
        return redirect('/login')->with('status', 'You have been logged out.');
    }
}
