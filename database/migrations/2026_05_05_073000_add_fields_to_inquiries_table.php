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
        Schema::table('inquiries', function (Blueprint $table) {
            if (! Schema::hasColumn('inquiries', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('inquiries', 'email')) {
                $table->string('email')->after('name');
            }
            if (! Schema::hasColumn('inquiries', 'subject')) {
                $table->string('subject')->after('email');
            }
            if (! Schema::hasColumn('inquiries', 'message')) {
                $table->text('message')->after('subject');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn(['name', 'email', 'subject', 'message']);
        });
    }
};
