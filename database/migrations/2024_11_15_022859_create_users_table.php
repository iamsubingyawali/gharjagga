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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            $table->foreignId('role_id')->constrained('user_roles', 'id')->cascadeOnDelete();
            $table->timestamps();
        });
        
        // Create a default Admin user
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@gharjagga.com',
            'phone' => '+9779800000000',
            'password' => bcrypt('gharjagga'),
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
