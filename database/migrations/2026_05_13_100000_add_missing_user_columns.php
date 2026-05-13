<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'current_streak')) {
                $table->integer('current_streak')->default(0)->after('email');
            }
            if (!Schema::hasColumn('users', 'badges')) {
                $table->json('badges')->nullable()->after('current_streak');
            }
            if (!Schema::hasColumn('users', 'currency_symbol')) {
                $table->string('currency_symbol')->default('৳')->after('badges');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['current_streak', 'badges', 'currency_symbol']);
        });
    }
};
