<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Blade;
use App\Support\AdminPanelAccess;
use App\Models\ManageReport;
use App\Models\LockWalletBalance;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrap();

        Blade::if('adminCan', function (string $permission) {
            return AdminPanelAccess::can($permission);
        });

        View::composer('member.*', function ($view) {
            if (session()->has('member_logged_in') && session('member_id')) {
                $balance = DB::table('manage_reports')
                    ->where('member_id', session('member_id'))
                    ->value('smart_wallet_balance') ?? 0;
                $lockedBalance = LockWalletBalance::where('member_id', session('member_id'))
                                        ->where('status', 1)
                                        ->sum('amount');

                $view->with('smartWalletBalance', $balance);
                $view->with('lockedWalletBalance', $lockedBalance);
            }
        });
    }
}
