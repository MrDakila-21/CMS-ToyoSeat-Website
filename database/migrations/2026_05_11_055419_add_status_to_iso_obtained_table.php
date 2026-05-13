<?php

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
        Schema::table('iso_obtained', function (Blueprint $table) {
            if (! Schema::hasColumn('iso_obtained', 'status')) {
                $table->enum('status', ['published', 'archived'])->default('published')->after('image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iso_obtained', function (Blueprint $table) {
            if (Schema::hasColumn('iso_obtained', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
