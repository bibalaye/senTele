<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('channels', function (Blueprint $table) {
            // Vérifier si la colonne n'existe pas avant de l'ajouter
            if (!Schema::hasColumn('channels', 'views_count')) {
                $table->integer('views_count')->default(0)->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('channels', function (Blueprint $table) {
            if (Schema::hasColumn('channels', 'views_count')) {
                $table->dropColumn('views_count');
            }
        });
    }
};
