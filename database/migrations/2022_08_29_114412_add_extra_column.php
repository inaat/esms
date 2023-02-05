<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('fee_transaction_payments', function (Blueprint $table) {
        // $table->integer('campus_id')->unsigned()->nullable()->after('fee_transaction_id');
        //     $table->foreign('campus_id')->references('id')->on('campuses');
        // });
        // Schema::table('expense_transaction_payments', function (Blueprint $table) {
        //     $table->integer('campus_id')->unsigned()->nullable()->after('expense_transaction_id');
        //     $table->foreign('campus_id')->references('id')->on('campuses');
        // });
        // Schema::table('hrm_transaction_payments', function (Blueprint $table) {
        //     $table->integer('campus_id')->unsigned()->nullable()->after('hrm_transaction_id');
        //     $table->foreign('campus_id')->references('id')->on('campuses');
        // });
        // Schema::table('regions', function (Blueprint $table) {
        //     $table->decimal('transport_fee', 22, 4)->default(0)->after('name');
        // });
        // Schema::table('expense_transactions', function (Blueprint $table) {
        //     $table->integer('vehicle_id')->unsigned()->nullable()->after('expense_for');
        //     $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
        // });
        Schema::table('hrm_employees', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->nullable()->after('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('students', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->nullable()->after('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
