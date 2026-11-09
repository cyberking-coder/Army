<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->unsignedTinyInteger('rating');
            $table->text('comment');
            $table->boolean('is_published')->default(false);
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
            $table->index('is_published');
        });
    }

    public function down(): void { Schema::dropIfExists('reviews'); }
};
