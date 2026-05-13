<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('iso_obtained', 'is_intro')) {
            Schema::table('iso_obtained', function (Blueprint $table) {
                $table->boolean('is_intro')->default(false)->after('is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('iso_obtained', 'is_intro')) {
            Schema::table('iso_obtained', function (Blueprint $table) {
                $table->dropColumn('is_intro');
            });
        }
    }
};
