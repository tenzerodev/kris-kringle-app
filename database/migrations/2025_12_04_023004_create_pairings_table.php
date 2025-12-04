<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pairings', function (Blueprint $table) {
            $table->id();
            
            // Link to the Group
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            
            // The Giver (Santa) - Links to Users table
            $table->foreignId('santa_id')->constrained('users')->cascadeOnDelete();
            
            // The Receiver (Baby) - Links to Users table
            $table->foreignId('baby_id')->constrained('users')->cascadeOnDelete();
            
            $table->timestamps();

            // Optional: Ensure a user can only be a Santa ONCE per group
            $table->unique(['group_id', 'santa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pairings');
    }
};