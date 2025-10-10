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
        if (!Schema::hasTable('room_participants')) {
            Schema::create('room_participants', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('room_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamp('joined_at')->useCurrent();
                $table->timestamp('last_read_at')->nullable();
                $table->timestamps();
                
                $table->unique(['room_id', 'user_id']);
                $table->index(['room_id']);
                $table->index(['user_id']);
            });
        } else {
            Schema::table('room_participants', function (Blueprint $table) {
                if (!Schema::hasColumn('room_participants', 'room_id')) {
                    $table->unsignedBigInteger('room_id')->after('id');
                    $table->index(['room_id']);
                }
                if (!Schema::hasColumn('room_participants', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->after('room_id');
                    $table->index(['user_id']);
                }
                if (!Schema::hasColumn('room_participants', 'joined_at')) {
                    $table->timestamp('joined_at')->useCurrent();
                }
                if (!Schema::hasColumn('room_participants', 'last_read_at')) {
                    $table->timestamp('last_read_at')->nullable();
                }
                if (!Schema::hasColumn('room_participants', 'created_at')) {
                    $table->timestamps();
                }
            });
        }

        if (Schema::hasTable('rooms') && Schema::hasColumn('room_participants', 'room_id')) {
            Schema::table('room_participants', function (Blueprint $table) {
                $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('users') && Schema::hasColumn('room_participants', 'user_id')) {
            Schema::table('room_participants', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_participants');
    }
};
