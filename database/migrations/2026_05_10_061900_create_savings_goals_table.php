<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('savings_goals', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->constrained()->onDelete('cascade');
            $blueprint->string('name');
            $blueprint->decimal('target_amount', 15, 2);
            $blueprint->decimal('current_amount', 15, 2)->default(0);
            $blueprint->date('deadline')->nullable();
            $blueprint->string('icon')->default('target');
            $blueprint->string('color')->default('#6366f1');
            $blueprint->enum('status', ['active', 'achieved'])->default('active');
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('savings_goals');
    }
};
