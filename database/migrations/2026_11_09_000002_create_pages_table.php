<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 200)->unique();
            $table->foreignId('parent_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->string('menu_name', 100);
            $table->longText('description');
            $table->string('meta_title', 160)->nullable();
            $table->string('meta_description', 300)->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->enum('status', ['draft', 'publish'])->default('draft');
            $table->timestamps();

            $table->index(['parent_id', 'sort']);
            $table->index('status');
        });
    }

    public function down(): void { Schema::dropIfExists('pages'); }
};
