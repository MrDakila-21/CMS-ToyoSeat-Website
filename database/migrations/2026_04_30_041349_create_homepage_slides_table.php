<?php
// database/migrations/2026_01_01_000001_create_homepage_slides_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomepageSlidesTable extends Migration
{
    public function up()
    {
        Schema::create('homepage_slides', function (Blueprint $table) {
            $table->id();
            $table->longText('image_data'); // Changed to longText for larger images
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('homepage_slides');
    }
}