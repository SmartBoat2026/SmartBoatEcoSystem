<?php

use Illuminate\Support\Facades\Route;

// ── Root Controllers ──────────────────────────────────────────────────────────
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegistrationController;

// ── Admin Folder Controllers ──────────────────────────────────────────────────
use App\Http\Controllers\Admin\AdminController as AdminPanelController;
use App\Http\Controllers\Admin\AdminpassivebonusController;
use App\Http\Controllers\Admin\BonusController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ManageReportController;
use App\Http\Controllers\Admin\PassivebonusController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductPurchaseController;
use App\Http\Controllers\Admin\SmartwalletController;
use App\Http\Controllers\Admin\StpscheduleController;
use App\Http\Controllers\Admin\SmartWalletMemberRequestController;
use App\Http\Controllers\Admin\StaffManageController;

// ── User Folder Controllers ───────────────────────────────────────────────────
use App\Http\Controllers\User\MemberController;
use App\Http\Controllers\User\MemberProductPurchaseController;
use App\Http\Controllers\User\MemberstpschedulesController;

use App\Http\Controllers\User\MemberPaymentController;
use App\Http\Controllers\User\SmartWallet\UserToUsersController;
use App\Http\Controllers\User\SmartWallet\CompanyPaymentController;
use App\Http\Controllers\User\SmartWallet\BuySellController;

// ─── Common Controllers ──────────────────────────────────────────────────

use App\Http\Controllers\ChatController;
// ─── Root: redirect to login ──────────────────────────────────────────────────
Route::get('/', function () {
    if (session()->has('admin_logged_in'))  return redirect('/admin-page');
    if (session()->has('member_logged_in')) return redirect('/member/dashboard');
    return view('admin.login');
});

// ─── Clear Cache ──────────────────────────────────────────────────
Route::get('/clear-cache', function () {
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    return "Cache Cleared ✅";
});

// ─── Login / Logout ───────────────────────────────────────────────────────────
Route::get('/login',  [AdminController::class, 'login'])->name('login');
Route::post('/login', [AdminController::class, 'doLogin'])->name('login.post');

// ─── Registration ─────────────────────────────────────────────────────────────
Route::get('/register',                                [RegistrationController::class, 'index'])->name('register');
Route::post('/register',                               [RegistrationController::class, 'store'])->name('register.post');
Route::get('/sponsor-lookup',                          [RegistrationController::class, 'sponsorLookup'])->name('sponsor.lookup');
Route::post('/payment/submit',                         [RegistrationController::class, 'paymentSubmit'])->name('payment.submit');
Route::get('/sponsor/search',                          [RegistrationController::class, 'sponsorSearch'])->name('sponsor.search');
Route::get('/sponsor/check-referral-verification',     [RegistrationController::class, 'referralCodeVerification'])->name('sponsor.check-referral-verification');


// ════════════════════════════════════════════════════════════════════════════════
//  ADMIN PANEL  (protected by admin.auth middleware)
// ════════════════════════════════════════════════════════════════════════════════
Route::middleware('admin.auth')->group(function () {

    // Logout
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    
    // Route::get('/admin/no-access', function () {
    //     return view('admin.errors.no-access');
    // })->name('admin.no-access');

    // Sub Admin Access
    Route::middleware('admin.super')->prefix('admin/staff')->name('admin.staff.')->group(function () {
        Route::get('/', [StaffManageController::class, 'index'])->name('index');
        Route::get('/create', [StaffManageController::class, 'create'])->name('create');
        Route::post('/', [StaffManageController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [StaffManageController::class, 'edit'])->name('edit')->whereNumber('id');
        Route::put('/{id}', [StaffManageController::class, 'update'])->name('update')->whereNumber('id');
        Route::delete('/{id}', [StaffManageController::class, 'destroy'])->name('destroy')->whereNumber('id');
    });

    Route::middleware('admin.panel')->group(function () {

    // Dashboard & Tasks
    Route::get('/admin-page', [AdminPanelController::class, 'index'])->name('admin.index');
    Route::get('/tasks',      [AdminPanelController::class, 'tasks'])->name('tasks');

    // ── Manage Member ─────────────────────────────────────────────────────────
    Route::get('/managereport',                         [ManageReportController::class, 'managereport'])->name('managereport');
    Route::get('/managereport/memberactive',            [ManageReportController::class, 'memberactive'])->name('managereport.memberactive');
    Route::post('/managereport/toggle-status/{id}',     [ManageReportController::class, 'toggleStatus'])->name('managereport.toggleStatus');
    Route::post('/managereport/store',                  [ManageReportController::class, 'store'])->name('managereport.store');
    Route::post('/managereport/update/{id}',            [ManageReportController::class, 'update'])->name('managereport.update');
    Route::get('/managereport/delete/{id}',             [ManageReportController::class, 'delete'])->name('managereport.delete');
    Route::post('/managereport/bulk-delete',            [ManageReportController::class, 'bulkDelete'])->name('managereport.bulkDelete');
    Route::get('/managereport/member-search',           [ManageReportController::class, 'memberSearch'])->name('managereport.member-search');
    Route::post('/managereport/access',                 [ManageReportController::class, 'accessMember'])->name('managereport.access');

    // ── Smart Wallet ──────────────────────────────────────────────────────────
    Route::get('/smartwallet',                          [SmartwalletController::class, 'smartwallet'])->name('smartwallet');
    Route::post('/smartwallet/store',                   [SmartwalletController::class, 'store'])->name('smartwallet.store');

    // ── Category ──────────────────────────────────────────────────────────────
    Route::get('/category',                             [CategoryController::class, 'category'])->name('category');
    Route::post('/category/store',                      [CategoryController::class, 'store'])->name('category.store');
    Route::post('/category/update/{id}',                [CategoryController::class, 'update'])->name('category.update');
    Route::get('/category/delete/{id}',                 [CategoryController::class, 'delete'])->name('category.delete');
    Route::post('/category/toggle-status/{id}',         [CategoryController::class, 'toggleStatus'])->name('category.toggleStatus');
    Route::post('/category/bulk-delete',                [CategoryController::class, 'bulkDelete'])->name('category.bulkDelete');

    // ── Product ───────────────────────────────────────────────────────────────
    Route::get('/product',                              [ProductController::class, 'product'])->name('product');
    Route::post('/product/store',                       [ProductController::class, 'store'])->name('product.store');
    Route::post('/product/update/{id}',                 [ProductController::class, 'update'])->name('product.update');
    Route::get('/product/delete/{id}',                  [ProductController::class, 'delete'])->name('product.delete');
    Route::post('/product/toggle-status/{id}',          [ProductController::class, 'toggleStatus'])->name('product.toggleStatus');
    Route::get('/product/subcategories/{categoryId}',   [ProductController::class, 'getSubcategories'])->name('product.subcategories');
    Route::post('/product/bulk-delete',                 [ProductController::class, 'bulkDelete'])->name('product.bulkDelete');

    // ── Product Purchase ──────────────────────────────────────────────────────
    Route::get('/productpurchase',                      [ProductPurchaseController::class, 'index'])->name('productpurchase.index');
    Route::post('/productpurchase/store',               [ProductPurchaseController::class, 'store'])->name('productpurchase.store');
    Route::get('/productpurchase/member',               [ProductPurchaseController::class, 'memberLookup'])->name('productpurchase.member');
    Route::post('/productpurchase/bulk-delete',         [ProductPurchaseController::class, 'bulkDelete'])->name('productpurchase.bulkDelete');
    Route::get('/productpurchase/member-wallet',        [ProductPurchaseController::class, 'memberWalletBalance'])->name('productpurchase.memberWallet');

    // ── STP Schedules ─────────────────────────────────────────────────────────
    Route::get('/stpschedules',                         [StpscheduleController::class, 'index'])->name('stpschedule.index');
    Route::get('/stpschedule/search-member',            [StpscheduleController::class, 'searchMember'])->name('stpschedule.searchMember');
    Route::post('/stpschedule/store',                   [StpscheduleController::class, 'store'])->name('stpschedule.store');
    Route::post('/stpschedule/update/{id}',             [StpscheduleController::class, 'update'])->name('stpschedule.update');
    Route::post('/stpschedule/delete/{id}',             [StpscheduleController::class, 'delete'])->name('stpschedule.delete');
    Route::post('/stpschedule/toggle-status/{id}',      [StpscheduleController::class, 'toggleStatus'])->name('stpschedule.toggleStatus');
    Route::post('/stpschedule/bulk-delete',             [StpscheduleController::class, 'bulkDelete'])->name('stpschedule.bulkDelete');

    // ── Bonus ─────────────────────────────────────────────────────────────────
    Route::get('/bonus',                                [BonusController::class, 'index'])->name('bonus.index');
    Route::get('/adminpassivebonus',                    [AdminpassivebonusController::class, 'passivebonus'])->name('adminpassivebonus');


    // ── Additional admin routes can be added here ─────────────────────────────────
    Route::get('/smart-wallet/memberRequest', [SmartWalletMemberRequestController::class, 'memberRequest'])->name('smartwallet.memberRequest.index');
    Route::get('/smart-wallet/memberRequest/load-model-open-data', [SmartWalletMemberRequestController::class, 'loadModelOpenData'])->name('smartwallet.memberRequest.loadModelOpenData');
    Route::get('/smart-wallet/memberRequest/list', [SmartWalletMemberRequestController::class, 'listData'])->name('smartwallet.memberRequest.list');
    Route::post('/smart-wallet/memberRequest/statusUpdate/{id}', [SmartWalletMemberRequestController::class, 'statusUpdate'])->name('smartwallet.memberRequest.statusUpdate');

    // ── Chat ───────────────────────────────────────────────────────────
    Route::get('/chat/load-name', [ChatController::class, 'loadChatName'])->name('chat.load.name');
    Route::get('/chat/load-history', [ChatController::class, 'loadChatHistory'])->name('chat.load.history');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    
   });
});


// ════════════════════════════════════════════════════════════════════════════════
//  CRON RUN
// ════════════════════════════════════════════════════════════════════════════════
Route::get('/bonus/insert-passive/{token}', function ($token) {
    if ($token !== env('CRON_SECRET')) abort(403);
    return app(BonusController::class)->insertPassiveBonus();
})->name('bonus.insertPassive');


// ════════════════════════════════════════════════════════════════════════════════
//  MEMBER (USER) PANEL  (protected by member.auth middleware)
// ════════════════════════════════════════════════════════════════════════════════
Route::middleware('member.auth')->prefix('member')->name('member.')->group(function () {

    // Logout
    Route::post('/logout',                              [MemberController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard',                            [MemberController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile',                              [MemberController::class, 'profile'])->name('profile');

    // ── Payment Details (CRUD) ────────────────────────────────────────────────


    Route::post('/payment/store',    [MemberController::class, 'paymentStore'])->name('payment.details.store');
    Route::post('/payment/destroy',  [MemberController::class, 'paymentDestroy'])->name('payment.details.destroy');
    Route::get('/payment',           [MemberController::class, 'paymentShow'])->name('payment.details.show');

    // Invoice
    Route::get('/invoice/{invoiceNo}',                  [MemberController::class, 'invoice'])->name('invoice');

    // Passive Bonus
    Route::get('/passivebonus',                         [PassivebonusController::class, 'passivebonus'])->name('passivebonus');

    // ── Product Purchase ──────────────────────────────────────────────────────
    Route::get('/productpurchase/purchaseList/{pagename}', [MemberProductPurchaseController::class, 'purchaseList'])->name('productpurchase.purchaseList');
    Route::get('/productpurchase',                         [MemberProductPurchaseController::class, 'index'])->name('productpurchase.index');
    Route::post('/productpurchase/store',                  [MemberProductPurchaseController::class, 'store'])->name('productpurchase.store');
    Route::get('/productpurchase/member',                  [MemberProductPurchaseController::class, 'memberLookup'])->name('productpurchase.member');
    Route::post('/productpurchase/bulk-delete',            [MemberProductPurchaseController::class, 'bulkDelete'])->name('productpurchase.bulkDelete');

    // ── STP Schedules ─────────────────────────────────────────────────────────
    Route::get('/memberstpschedules',                      [MemberstpschedulesController::class, 'index'])->name('memberstpschedules.index');
    Route::get('/memberstpschedules/search-member',        [MemberstpschedulesController::class, 'searchMember'])->name('memberstpschedules.searchMember');
    Route::post('/memberstpschedules/store',               [MemberstpschedulesController::class, 'store'])->name('memberstpschedules.store');
    Route::post('/memberstpschedules/update/{id}',         [MemberstpschedulesController::class, 'update'])->name('memberstpschedules.update');
    Route::post('/memberstpschedules/delete/{id}',         [MemberstpschedulesController::class, 'delete'])->name('memberstpschedules.delete');
    Route::post('/memberstpschedules/toggle-status/{id}',  [MemberstpschedulesController::class, 'toggleStatus'])->name('memberstpschedules.toggleStatus');
    Route::post('/memberstpschedules/bulk-delete',         [MemberstpschedulesController::class, 'bulkDelete'])->name('memberstpschedules.bulkDelete');


    // ── Payment Details ───────────────────────────────────────────────────────────
    Route::post('/payment-details/store',  [MemberPaymentController::class, 'store'])->name('payment.details.store');
    Route::get('/payment-details',         [MemberPaymentController::class, 'show'])->name('payment.details.show');

    // ── Smart Wallet ───────────────────────────────────────────────────────────
        //── user to user smart wallet routes───────────────────────────────────────────────────────────
    Route::get('/smart-wallet/sender',      [UserToUsersController::class, 'sender'])->name('smartwallet.userToUser.sender');
    Route::get('/smart-wallet/senderList',  [UserToUsersController::class, 'senderList'])->name('smartwallet.userToUser.senderList');
    Route::get('/smart-wallet/members',     [UserToUsersController::class, 'getMembers'])->name('smartwallet.userToUser.members');
    Route::delete('/smart-wallet/deleteOne/{id}', [UserToUsersController::class, 'deleteOne'])->name('smartwallet.userToUser.deleteOne');
    Route::post('/member/smart-wallet/bulk-delete', [UserToUsersController::class, 'bulkDelete'])->name('smartwallet.userToUser.bulkDelete');
    Route::post('/smart-wallet/store',      [UserToUsersController::class, 'store'])->name('smartwallet.userToUser.store');
    

    Route::get('/smart-wallet/receiver',    [UserToUsersController::class, 'receiver'])->name('smartwallet.userToUser.receiver');
    Route::get('/smart-wallet/receiverList', [UserToUsersController::class, 'receiverList'])->name('smartwallet.userToUser.receiverList');
   
    // ── Via Company Payment Submission───────────────────────────────────────────────────────────
    Route::get('/smart-wallet/company-payment', [CompanyPaymentController::class, 'companyPayment'])->name('smartwallet.companyPayment.index');
    Route::get('/smart-wallet/company-payment/load-model-open-data', [CompanyPaymentController::class, 'loadModelOpenData'])->name('smartwallet.companyPayment.loadModelOpenData');
    Route::get('/smart-wallet/company-payment/list', [CompanyPaymentController::class, 'listData'])->name('smartwallet.companyPayment.list');
    Route::post('/smart-wallet/company-payment/store', [CompanyPaymentController::class, 'store'])->name('smartwallet.companyPayment.store');
    
    // ── Buy/Sell───────────────────────────────────────────────────────────
    Route::get('/smart-wallet/buy-sell/selfSell', [BuySellController::class, 'selfSell'])->name('smartwallet.buySell.selfSell');
    Route::post('/smart-wallet/buy-sell/selfSell-store', [BuySellController::class, 'selfSellStore'])->name('smartwallet.buySell.selfSellStore');
    Route::get('/smart-wallet/buy-sell/selfSelllist', [BuySellController::class, 'selfSellListData'])->name('smartwallet.buySell.selfSellListData');
    

    Route::get('/smart-wallet/buy-sell/load-model-open-data', [BuySellController::class, 'loadModelOpenData'])->name('smartwallet.buySell.loadModelOpenData');
    



    // ── Chat ───────────────────────────────────────────────────────────
    Route::get('/chat/load-name', [ChatController::class, 'loadChatName'])->name('chat.load.name');
    Route::get('/chat/load-history', [ChatController::class, 'loadChatHistory'])->name('chat.load.history');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');

    
});

