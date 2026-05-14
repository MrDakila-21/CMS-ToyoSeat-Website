<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDynamicCategoriesToOverviewContents extends Migration
{
    public function up()
    {
        Schema::table('overview_contents', function (Blueprint $table) {
            $table->json('dynamic_categories')->nullable()->after('employees');
        });
    }
    
    public function down()
    {
        Schema::table('overview_contents', function (Blueprint $table) {
            $table->dropColumn('dynamic_categories');
        });
    }
}