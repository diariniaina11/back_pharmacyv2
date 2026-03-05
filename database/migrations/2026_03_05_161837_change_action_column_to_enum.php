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
        Schema::table('logs', function (Blueprint $table) {
            $table->enum('action', [
                'produitPlus', 'produitMoins', 'produitSupp', 'produitNew',
                'venteMois', 'ventePlus', 'venteSupp', 'venteNew',
                'categNew', 'categSupp'
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->string('action', 50)->change();
        });
    }
};
