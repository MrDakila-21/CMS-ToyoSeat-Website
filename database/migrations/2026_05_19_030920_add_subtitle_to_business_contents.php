<?php
// database/migrations/xxxx_xx_xx_add_subtitle_to_business_contents.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubtitleToBusinessContents extends Migration
{
    public function up()
    {
        Schema::table('business_contents', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('title');
        });
    }

    public function down()
    {
        Schema::table('business_contents', function (Blueprint $table) {
            $table->dropColumn('subtitle');
        });
    }
}