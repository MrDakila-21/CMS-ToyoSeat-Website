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
        Schema::table('users', function (Blueprint $table) {
            // Add display_name column (for showing user's name in UI)
            $table->string('display_name')->after('name');
            
            // Add account_type column (admin or superadmin)
            $table->enum('account_type', ['admin', 'superadmin'])->default('admin')->after('display_name');
            
            // Add is_active column for account activation/deactivation
            $table->boolean('is_active')->default(true)->after('account_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['display_name', 'account_type', 'is_active']);
        });
    }
};