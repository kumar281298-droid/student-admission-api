<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('college_id')->constrained('colleges')->cascadeOnDelete();
            $table->string('name');
            $table->string('code');
            $table->string('duration');
            $table->unsignedInteger('total_seats');
            $table->unsignedInteger('available_seats')->index();
            $table->string('status')->default('ACTIVE')->index();
            $table->timestamps();

            $table->unique(['college_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
