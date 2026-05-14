<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTelephoneToCompanyLocationsTable extends Migration
{
    public function up()
    {
        Schema::table('company_locations', function (Blueprint $table) {
            $table->string('telephone', 50)->nullable()->after('phone');
        });
    }

    public function down()
    {
        Schema::table('company_locations', function (Blueprint $table) {
            $table->dropColumn('telephone');
        });
    }
}