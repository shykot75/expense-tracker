<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('budget_plans');
        
        Schema::create('budget_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('monthly_income', 15, 2);
            $table->integer('cycle_start_date')->default(1);
            $table->integer('needs_percentage')->default(60);
            $table->integer('wants_percentage')->default(25);
            $table->integer('savings_percentage')->default(15);
            $table->decimal('needs_amount', 15, 2);
            $table->decimal('wants_amount', 15, 2);
            $table->decimal('savings_amount', 15, 2);
            $table->timestamps();
            $table->auditColumns();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_plans');
    }
};
