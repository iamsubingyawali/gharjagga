<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('address');
            $table->string('rent');
            $table->string('image');
            $table->string('area');
            $table->string('no_of_bedrooms');
            $table->string('no_of_bathrooms');
            $table->foreignId('created_by')->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('type_id')->constrained('property_types', 'id')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
