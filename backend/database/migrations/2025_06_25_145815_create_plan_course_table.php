<?php

use App\Models\Course;
use App\Models\Plan;
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
        Schema::create('plan_course', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Plan::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Course::class)->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_course');
    }
};
