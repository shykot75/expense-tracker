<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // We use raw SQL because modifying enum columns via Blueprint requires doctrine/dbal and can be problematic
        DB::statement("ALTER TABLE loans MODIFY COLUMN loan_type ENUM('lent', 'borrowed') NOT NULL");
        DB::statement("ALTER TABLE loans MODIFY COLUMN status ENUM('active', 'paid') NOT NULL DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE loans MODIFY COLUMN loan_type ENUM('given', 'taken') NOT NULL");
        DB::statement("ALTER TABLE loans MODIFY COLUMN status ENUM('pending', 'settled') NOT NULL DEFAULT 'pending'");
    }
};
