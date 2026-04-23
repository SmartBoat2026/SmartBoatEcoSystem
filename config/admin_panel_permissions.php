<?php

/**
 * Admin panel permission keys (assigned to staff) mapped to route names for middleware.
 * Route values: single permission key, or array of keys (staff needs any one).
 */
return [

    'modules' => [
        'dashboard' => 'Dashboard & Tasks',
        'manage_member' => 'Manage Member (list, add, edit, access)',
        'member_activation' => 'Member Activation Requests',
        'smart_wallet' => 'Smart Wallet',
        'smart_wallet_member_requests' => 'Smart Wallet Member Requests',
        'category' => 'Category',
        'product' => 'Product',
        'product_purchase' => 'Product Purchase',
        'stp_schedule' => 'STP Schedule',
        'bonus' => 'Bonus',
        'bonus_passive' => 'Passive Bonus',
        'chat' => 'Chat',
    ],

    /** After staff login: first module they can access (key => route name). */
    'staff_login_redirect_order' => [
        'dashboard' => 'admin.index',
        'chat' => 'admin.index',
        'manage_member' => 'managereport',
        'member_activation' => 'managereport.memberactive',
        'smart_wallet' => 'smartwallet',
        'smart_wallet_member_requests' => 'smartwallet.memberRequest.index',
        'category' => 'category',
        'product' => 'product',
        'product_purchase' => 'productpurchase.index',
        'stp_schedule' => 'stpschedule.index',
        'bonus' => 'bonus.index',
        'bonus_passive' => 'adminpassivebonus',
        'chat' => 'admin.index',
    ],

    /**
     * Laravel route name => permission key, or array of keys (any grants access).
     */
    'route_permissions' => [
        // Landing pages: allow dashboard and/or chat-only staff to have a home screen.
        'admin.index' => ['dashboard', 'chat'],
        'tasks' => ['dashboard', 'chat'],

        'managereport' => 'manage_member',
        'managereport.store' => 'manage_member',
        'managereport.update' => 'manage_member',
        'managereport.delete' => 'manage_member',
        'managereport.bulkDelete' => 'manage_member',
        'managereport.member-search' => 'manage_member',
        'managereport.access' => 'manage_member',

        'managereport.memberactive' => 'member_activation',
        'managereport.toggleStatus' => ['manage_member', 'member_activation'],

        'smartwallet' => 'smart_wallet',
        'smartwallet.store' => 'smart_wallet',

        'smartwallet.memberRequest.index' => 'smart_wallet_member_requests',
        'smartwallet.memberRequest.loadModelOpenData' => 'smart_wallet_member_requests',
        'smartwallet.memberRequest.list' => 'smart_wallet_member_requests',
        'smartwallet.memberRequest.statusUpdate' => 'smart_wallet_member_requests',

        'category' => 'category',
        'category.store' => 'category',
        'category.update' => 'category',
        'category.delete' => 'category',
        'category.toggleStatus' => 'category',
        'category.bulkDelete' => 'category',

        'product' => 'product',
        'product.store' => 'product',
        'product.update' => 'product',
        'product.delete' => 'product',
        'product.toggleStatus' => 'product',
        'product.subcategories' => 'product',
        'product.bulkDelete' => 'product',

        'productpurchase.index' => 'product_purchase',
        'productpurchase.store' => 'product_purchase',
        'productpurchase.member' => 'product_purchase',
        'productpurchase.bulkDelete' => 'product_purchase',
        'productpurchase.memberWallet' => 'product_purchase',

        'stpschedule.index' => 'stp_schedule',
        'stpschedule.searchMember' => 'stp_schedule',
        'stpschedule.store' => 'stp_schedule',
        'stpschedule.update' => 'stp_schedule',
        'stpschedule.delete' => 'stp_schedule',
        'stpschedule.toggleStatus' => 'stp_schedule',
        'stpschedule.bulkDelete' => 'stp_schedule',

        'bonus.index' => 'bonus',
        'adminpassivebonus' => 'bonus_passive',

        'chat.load.name' => 'chat',
        'chat.load.history' => 'chat',
        'chat.send' => 'chat',
    ],
];
