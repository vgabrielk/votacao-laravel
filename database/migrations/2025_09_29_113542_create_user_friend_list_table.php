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
        Schema::create('user_friend_list', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->foreignId('friend_id')->constrained('users')->onDelete('cascade');

            $table->enum('status', ['pending', 'accepted', 'blocked'])->default('pending');

            $table->foreignId('invited_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();

            $table->unique(['user_id', 'friend_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_friend_list');
    }
};
