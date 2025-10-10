<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verificar se a tabela polls existe
        if (!Schema::hasTable('polls')) {
            return;
        }

        // Para SQLite, não precisamos verificar foreign keys da mesma forma
        // SQLite não suporta DROP FOREIGN KEY da mesma forma que MySQL

        // Verificar e remover colunas se existirem
        Schema::table('polls', function (Blueprint $table) {
            if (Schema::hasColumn('polls', 'group_id')) {
                $table->dropColumn('group_id');
            }
            
            if (Schema::hasColumn('polls', 'scope')) {
                $table->dropColumn('scope');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('polls', function (Blueprint $table) {
            if (!Schema::hasColumn('polls', 'group_id')) {
                $table->foreignId('group_id')->nullable()->constrained('groups');
            }
            
            if (!Schema::hasColumn('polls', 'scope')) {
                $table->enum('scope', ['group', 'friends'])->default('group');
            }
        });
    }
};