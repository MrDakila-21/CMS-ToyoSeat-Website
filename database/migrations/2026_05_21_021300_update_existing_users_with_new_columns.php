<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update existing users to have default values for new columns
        DB::table('users')->whereNull('display_name')->update([
            'display_name' => DB::raw('name')
        ]);
        
        DB::table('users')->whereNull('account_type')->update([
            'account_type' => 'superadmin'
        ]);
        
        DB::table('users')->whereNull('is_active')->update([
            'is_active' => true
        ]);
    }

    public function down(): void
    {
        // No need to revert
    }
};