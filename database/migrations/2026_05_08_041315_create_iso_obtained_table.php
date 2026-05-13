<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iso_obtained', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_intro')->default(false)->comment('true = ISO Introduction, false = Manage ISO Obtained Content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iso_obtained');
    }
};
