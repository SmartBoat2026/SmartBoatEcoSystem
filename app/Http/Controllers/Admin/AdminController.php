<?php
// FILE: app/Http/Controllers/AdminController.php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    // ─── Show Login Page ─────────────────────────────────────────────────────
    // public function login()
    // {
    //     if (session()->has('admin_logged_in')) {
    //         return redirect('/admin-page');
    //     }
    //     if (session()->has('member_logged_in')) {
    //         return redirect('/member/dashboard');
    //     }
    //     return view('admin.login');
    // }

    // ─── Handle Login POST (Dual: Admin OR Member) ────────────────────────────
    // public function doLogin(Request $request)
    // {
    //     $request->validate([
    //         'username' => 'required|string',
    //         'password' => 'required|string',
    //     ]);

    //     $username = trim($request->username);
    //     $password = trim($request->password);

    //     // ── 1. Try Admin table first ──────────────────────────────────────────
    //     $admin = Admin::where('username', $username)->first();

    //     if ($admin && $admin->verifyPassword($password)) {
    //         session([
    //             'admin_logged_in' => true,
    //             'admin_id'        => $admin->admin_id,
    //             'admin_name'      => $admin->name,
    //             'admin_username'  => $admin->username,
    //             'admin_role'      => $admin->role,
    //             'type'            => 'Admin',
    //         ]);
    //         return redirect('/admin-page')->with('success', 'Welcome back, ' . $admin->name . '!');
    //     }

    //     // ── 2. Try Member (manage_reports) — login via memberID OR name ───────
    //     $member = ManageReport::where('memberID', $username)
    //                 ->orWhere('name', $username)
    //                 ->first();

    //     if ($member && $member->verifyPassword($password)) {

    //         // // ✅ STATUS CHECK
    //         // if ($member->status == 2) {
    //         //     return back()->withErrors([
    //         //         'username' => 'Your account is pending verification. Please wait for approval.'
    //         //     ])->withInput(['username' => $username]);
    //         // }

    //         if ($member->status == 3) {
    //             return back()->withErrors([
    //                 'username' => 'Your account has been blocked. Please contact support.'
    //             ])->withInput(['username' => $username]);
    //         }

    //             session([
    //                 'member_logged_in'  => true,
    //                 'member_id'         => $member->member_id,
    //                 'member_memberID'   => $member->memberID,
    //                 'member_name'       => $member->name,
    //                 'member_email'      => $member->email,
    //                 'member_phone'      => $member->phone,
    //                 'member_smart_pts'  => $member->smart_point,
    //                 'member_smart_qty'  => $member->smart_quanity,
    //                 'member_sponsor'    => $member->sponser_id,
    //                  'type'             => 'Member',
    //                  'status'             => $member->status,
    //             ]);
    //             return redirect('/member/dashboard')->with('success', 'Welcome, ' . $member->name . '!');
    //     }

    //     // ── 3. Neither matched ────────────────────────────────────────────────
    //     return back()->withErrors([
    //         'username' => 'Invalid Member ID / Username or Password.',
    //     ])->withInput(['username' => $username]);
    // }

    // ─── Admin Logout ─────────────────────────────────────────────────────────
    // public function logout(Request $request)
    // {
    //     $request->session()->forget([
    //         'admin_logged_in', 'admin_id', 'admin_name',
    //         'admin_username', 'admin_role',
    //     ]);
    //     return redirect('/login')->with('status', 'You have been logged out.');
    // }

    // ─── Admin Dashboard ──────────────────────────────────────────────────────
    public function index()
    {
        return view('admin.index');
    }

    // ─── Admin Tasks ──────────────────────────────────────────────────────────
    public function tasks()
    {
        return view('admin.tasks');
    }
}
