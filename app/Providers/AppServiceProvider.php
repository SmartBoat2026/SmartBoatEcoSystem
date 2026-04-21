<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrap();

        View::composer('member.*', function ($view) {
            if (session()->has('member_logged_in') && session('member_id')) {
                $balance = DB::table('manage_reports')
                    ->where('member_id', session('member_id'))
                    ->value('smart_wallet_balance') ?? 0;

                $view->with('smartWalletBalance', $balance);
            }
        });
    }
}
