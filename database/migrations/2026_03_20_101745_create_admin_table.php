<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->increments('admin_id');

            $table->text('username');
            $table->text('password');
            $table->integer('role');

            $table->tinyInteger('kyc_module');
            $table->tinyInteger('epin_module');

            $table->text('name');

            $table->tinyInteger('team_management_module');
            $table->tinyInteger('product_management_module');
            $table->tinyInteger('franchise_module');
            $table->tinyInteger('stock_module');
            $table->tinyInteger('payout_module');
            $table->tinyInteger('report_module');

            $table->integer('mpayout_module');

            $table->tinyInteger('support_module');
            $table->tinyInteger('news_module');

            $table->text('bank_detail');
            $table->text('qr_code');
            $table->text('upi');

            $table->integer('course_points');

            $table->text('website_title');
            $table->text('web_logo');
            $table->text('web_address');
            $table->string('web_phone');
            $table->text('web_link');
            $table->text('web_email');

            $table->double('passive_bonus');
            $table->double('tds');
            $table->double('tds_without_kyc');
            $table->double('admin_charge');
            $table->double('admin_charge_without_kyc');
            $table->double('monthly_renewal');
            $table->double('yearly_renewal');

            $table->integer('member_downline');
            $table->integer('foundership');
            $table->integer('monthly_rewards');
            $table->integer('member_income');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
