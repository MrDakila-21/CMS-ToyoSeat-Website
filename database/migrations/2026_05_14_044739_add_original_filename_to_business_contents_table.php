<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('business_contents', function (Blueprint $table) {
        $table->string('original_filename')->nullable()->after('image');
    });
}

    /**
     * Reverse the migrations.
     */
public function down()
{
    Schema::table('business_contents', function (Blueprint $table) {
        $table->dropColumn('original_filename');
    });
}
};
