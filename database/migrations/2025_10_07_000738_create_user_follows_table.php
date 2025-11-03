<?php

use App\Models\User;
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
        Schema::create('user_follows', function (Blueprint $table) {
            $table->id();

            // The user who follows
            $table->foreignId('follower_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // The user being followed
            $table->foreignId('following_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
            // Prevent duplicate follows (same follower/following pair)
            $table->unique(['follower_user_id', 'following_user_id'], 'user_follows_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_follows');
    }
};
