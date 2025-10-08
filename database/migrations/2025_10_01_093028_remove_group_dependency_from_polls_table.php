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
        Schema::table('polls', function (Blueprint $table) {
            // Verificar se a foreign key existe antes de removê-la
            if (Schema::hasColumn('polls', 'group_id')) {
                $table->dropForeign(['group_id']);
                $table->dropColumn('group_id');
            }
            
            // Verificar se a coluna scope existe antes de removê-la
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
            $table->foreignId('group_id')->nullable()->constrained('groups');
            $table->enum('scope', ['group', 'friends'])->default('group');
        });
    }
};