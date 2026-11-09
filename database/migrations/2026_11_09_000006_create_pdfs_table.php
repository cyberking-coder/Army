<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pdfs', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 200)->unique();
            $table->text('description')->nullable();
            $table->string('file_path', 500);
            $table->unsignedInteger('file_size_kb')->default(0);
            $table->string('category', 100)->nullable();
            $table->unsignedInteger('downloads_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('category');
        });
    }

    public function down(): void { Schema::dropIfExists('pdfs'); }
};
