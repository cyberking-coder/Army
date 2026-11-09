<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('result_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('mobile', 20);
            $table->string('email', 150);
            $table->string('roll_no', 50);
            $table->string('zone', 100);
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            $table->index(['exam_id', 'zone']);
            $table->index('roll_no');
        });
    }

    public function down(): void { Schema::dropIfExists('result_registrations'); }
};
