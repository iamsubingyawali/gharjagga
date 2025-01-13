<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('property_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Insert default property types
        DB::table('property_types')->insert([
            ['name' => 'House', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Flat', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Room', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Apartment', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Office Space', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Shop Space', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Commercial Building', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Land', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Villa', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bungalow', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_types');
    }
};
