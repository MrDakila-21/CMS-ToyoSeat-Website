<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOverviewContentsTable extends Migration
{
    public function up()
    {
        Schema::create('overview_contents', function (Blueprint $table) {
            $table->id();
            
            // Business Principles (repeater/array)
            $table->json('business_principles')->nullable();
            
            // Message from President
            $table->text('president_message')->nullable();
            $table->string('president_name')->nullable();
            $table->string('president_title')->nullable();
            $table->string('president_image')->nullable();
            
            // Company Profile
            $table->text('company_profile')->nullable();
            $table->string('company_profile_image')->nullable();
            
            // Additional info
            $table->string('company_name')->nullable();
            $table->string('established_date')->nullable();
            $table->string('capital')->nullable();
            $table->string('president_representative')->nullable();
            $table->text('business_description')->nullable();
            $table->integer('employees')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('overview_contents');
    }
}