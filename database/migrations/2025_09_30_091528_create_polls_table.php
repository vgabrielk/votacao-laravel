<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups');
            $table->foreignId('creator_id')->constrained('users');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['public', 'private'])->default('public');
            $table->boolean('anonymus')->default(false);
            $table->boolean('allow_multiple')->default(false);
            $table->date('start_at');
            $table->date('end_at')->nullable();
            $table->enum('status', ['draft', 'open', 'closed']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polls');
    }
};
