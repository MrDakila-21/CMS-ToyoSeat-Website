<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('company_locations', function (Blueprint $table) {
            $table->id();
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country');
            $table->text('google_maps_embed')->nullable(); // For iframe embed code
            $table->string('latitude')->nullable(); // For map coordinates
            $table->string('longitude')->nullable(); // For map coordinates
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('working_hours')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_locations');
    }
};