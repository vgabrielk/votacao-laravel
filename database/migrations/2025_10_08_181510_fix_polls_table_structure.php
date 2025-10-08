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

        // Verificar e remover foreign key constraint se existir
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'polls' 
            AND COLUMN_NAME = 'group_id' 
            AND CONSTRAINT_NAME != 'PRIMARY'
        ");

        foreach ($foreignKeys as $foreignKey) {
            Schema::table('polls', function (Blueprint $table) use ($foreignKey) {
                $table->dropForeign($foreignKey->CONSTRAINT_NAME);
            });
        }

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