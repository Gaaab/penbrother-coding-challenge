<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('amortizations', function (Blueprint $table) {
            $table->decimal('amount', 13, 5)->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount', 13, 5)->change();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('wallet_balance', 13, 5)->change();
            $table->decimal('investment_goal', 13, 5)->change();
        });
    }

    public function down(): void
    {
        Schema::table('amortizations', function (Blueprint $table) {
            $table->bigInteger('amount')->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->bigInteger('amount')->change();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->bigInteger('wallet_balance')->change();
            $table->bigInteger('investment_goal')->change();
        });
    }
};
