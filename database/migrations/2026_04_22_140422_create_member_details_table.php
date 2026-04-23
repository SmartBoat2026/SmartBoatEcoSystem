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
        Schema::create('member_details', function (Blueprint $table) {

            $table->id('member_id');

            $table->string('regionbelongs', 10);
            $table->tinyInteger('isFranchisee');
            $table->string('memberID', 100);
            $table->string('sponser_name', 255);

            $table->integer('smart_point');
            $table->string('smart_quanity', 11);

            $table->double('company_autopool_income');
            $table->float('wallet_balance');
            $table->double('wallet_balance_top_up');
            $table->float('award_fund');
            $table->float('travel_fund');
            $table->double('car_fund');

            $table->float('market_expensive');
            $table->double('active_bonus');
            $table->float('consistency_bonus');
            $table->float('super_active_bonus');
            $table->float('delex_active_bonus');
            $table->float('flag_ship_bonus');

            $table->string('salutation', 20);
            $table->string('name', 100);
            $table->string('date_of_birth', 100);
            $table->string('email', 100);
            $table->string('countryname', 25);
            $table->string('phone', 100);
            $table->string('alt_phone', 100);

            $table->string('username', 100);
            $table->string('password', 100);
            $table->string('transaction_password', 100);

            $table->integer('authorization_ID');

            $table->string('sponser_id', 100);
            $table->string('referenceID', 100);
            $table->string('position', 100);
            $table->integer('signup_package');
            $table->string('epin', 100);

            $table->text('address');
            $table->string('city', 100);
            $table->string('joining_date', 100);

            $table->string('placement_leg', 100);
            $table->string('placementID', 100);
            $table->string('binary_referenceID', 100);
            $table->string('bianry_palcementLeg', 123);

            $table->string('bank_name', 100);
            $table->string('bank_acc_no', 100);
            $table->string('bank_brn_name', 100);
            $table->text('profile_pic');
            $table->string('ifsc_code', 100);

            $table->string('nominee_name', 100);
            $table->string('nominee_relation', 100);
            $table->string('id_proof', 100);

            $table->string('rank', 100)->default('MEMBER');

            $table->string('registration_date_time', 255);
            $table->string('ip_address', 100);

            $table->tinyInteger('status');
            $table->tinyInteger('balance_update_afer_login');
            $table->tinyInteger('updatedProfile');

            $table->text('remark');
            $table->string('last_updated_by', 100);

            $table->string('panNumber', 35);
            $table->tinyInteger('completeProfile');
            $table->tinyInteger('user_role');

            $table->string('date_created', 100);
            $table->string('state', 100);
            $table->string('pin_code', 100);

            $table->tinyInteger('repeated');
            $table->tinyInteger('added_from_website');

            $table->text('fileToUpload_adhar');
            $table->text('fileToUpload_pan');
            $table->text('fileToUpload_kyc');

            $table->integer('referenceIDNo');

            $table->integer('pv_carry_forward_left');
            $table->integer('pv_carry_forward_right');
            $table->integer('current_pv_left');
            $table->integer('current_pv_right');
            $table->integer('total_pv_left');
            $table->integer('total_pv_right');

            $table->integer('current_bv_left');
            $table->integer('current_bv_right');
            $table->integer('total_bv_left');
            $table->integer('total_bv_right');

            $table->tinyInteger('sequence');

            $table->integer('bv_carry_forward_right');
            $table->integer('bv_carry_forward_left');

            $table->text('adhar_front_image');
            $table->text('adhar_back_image');
            $table->text('pan_card');
            $table->text('my_photo');
            $table->text('passbook');

            $table->tinyInteger('kyc_approved');
            $table->string('performed_by', 255);

            $table->double('recharge_wallet');

            $table->tinyInteger('blocking');

            $table->double('wallet_1');
            $table->double('wallet_2');

            $table->string('global_sponser_id', 255);

            $table->integer('level_details');

            $table->string('phone_pay', 255);
            $table->string('google_pay', 255);

            $table->tinyInteger('entry_club_member');

            $table->text('payment_details');
            $table->text('transaction_id');
            $table->text('transaction_screenshot');

            $table->text('smbank_name');
            $table->text('smbank_acc_no');
            $table->text('smbank_brn_name');
            $table->text('smbank_ifsc_code');
            $table->text('qr_code');

            $table->dateTime('registration_datetime')->nullable();
            $table->decimal('staking_coin', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_details');
    }
};
