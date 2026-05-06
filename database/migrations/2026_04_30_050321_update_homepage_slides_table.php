<?php
// database/migrations/2026_01_15_000001_update_homepage_slides_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHomepageSlidesTable extends Migration
{
    public function up()
    {
        Schema::table('homepage_slides', function (Blueprint $table) {
            // Drop the old image_data column
            if (Schema::hasColumn('homepage_slides', 'image_data')) {
                $table->dropColumn('image_data');
            }
            // Add new image_path column
            $table->string('image_path')->after('id');
        });
    }

    public function down()
    {
        Schema::table('homepage_slides', function (Blueprint $table) {
            $table->dropColumn('image_path');
            $table->longText('image_data')->after('id');
        });
    }
}