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
        if (!Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                // Use plain columns first to avoid FK issues when order differs in prod
                $table->unsignedBigInteger('room_id');
                $table->unsignedBigInteger('user_id');
                $table->text('content');
                $table->string('type')->default('text');
                $table->json('metadata')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->index(['room_id']);
                $table->index(['user_id']);
            });
        } else {
            // Ensure required columns/indexes exist if table was pre-created
            Schema::table('messages', function (Blueprint $table) {
                if (!Schema::hasColumn('messages', 'room_id')) {
                    $table->unsignedBigInteger('room_id')->after('id');
                    $table->index(['room_id']);
                }
                if (!Schema::hasColumn('messages', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->after('room_id');
                    $table->index(['user_id']);
                }
                if (!Schema::hasColumn('messages', 'content')) {
                    $table->text('content')->after('user_id');
                }
                if (!Schema::hasColumn('messages', 'type')) {
                    $table->string('type')->default('text')->after('content');
                }
                if (!Schema::hasColumn('messages', 'metadata')) {
                    $table->json('metadata')->nullable()->after('type');
                }
                if (!Schema::hasColumn('messages', 'read_at')) {
                    $table->timestamp('read_at')->nullable()->after('metadata');
                }
                if (!Schema::hasColumn('messages', 'created_at')) {
                    $table->timestamps();
                }
            });
        }

        // Add foreign keys only if referenced tables exist (robust for prod deploy order)
        if (Schema::hasTable('rooms') && Schema::hasColumn('messages', 'room_id')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('users') && Schema::hasColumn('messages', 'user_id')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
