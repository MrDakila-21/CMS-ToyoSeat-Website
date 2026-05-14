<?php
// database/migrations/2024_01_01_000001_create_business_contents_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessContentsTable extends Migration
{
    public function up()
    {
        Schema::create('business_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section'); // automotive, organization, characteristic, partnership
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('name')->nullable(); // for organization
            $table->string('position')->nullable(); // for organization
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index(['section', 'order']);
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_contents');
    }
}