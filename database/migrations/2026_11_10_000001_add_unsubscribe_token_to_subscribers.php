<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('officer_subscribers', function (Blueprint $table) {
            $table->string('unsubscribe_token', 64)->nullable()->unique()->after('ip_address');
        });

        Schema::table('soldier_subscribers', function (Blueprint $table) {
            $table->string('unsubscribe_token', 64)->nullable()->unique()->after('ip_address');
        });
    }

    public function down(): void
    {
        Schema::table('officer_subscribers', function (Blueprint $table) {
            $table->dropColumn('unsubscribe_token');
        });
        Schema::table('soldier_subscribers', function (Blueprint $table) {
            $table->dropColumn('unsubscribe_token');
        });
    }
};
