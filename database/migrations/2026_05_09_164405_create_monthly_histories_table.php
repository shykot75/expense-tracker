<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('billing_month'); // Format: YYYY-MM
            $table->decimal('total_income', 10, 2);
            $table->decimal('needs_spent', 10, 2);
            $table->decimal('wants_spent', 10, 2);
            $table->decimal('total_saved', 10, 2);
            $table->string('report_file_path')->nullable();
            $table->timestamps();
            $table->auditColumns();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_histories');
    }
};
