<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Adiciona a constraint única.
     */
    public function up(): void
    {
        Schema::table('group_members', function (Blueprint $table) {
            // adiciona um índice único para evitar duplicidade
            $table->unique(['group_id', 'user_id'], 'group_user_unique');
        });
    }

    /**
     * Remove a constraint (rollback).
     */
    public function down(): void
    {
        Schema::table('group_members', function (Blueprint $table) {
            $table->dropUnique('group_user_unique');
        });
    }
};
