<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('study_material', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 200)->unique();
            $table->text('description')->nullable();
            $table->string('file_path', 500);
            $table->string('subject', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('subject');
        });
    }

    public function down(): void { Schema::dropIfExists('study_material'); }
};
