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
        Schema::table('reports', function (Blueprint $table) {
            $table->string('sales')->default(0)->change();
            $table->string('purchases')->default(0)->change();
            $table->string('expenses')->default(0)->change();
            $table->string('income')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('sales', 'purchase', 'expenses', 'income')->change();
            $table->string('purchases')->change();
            $table->string('expenses')->change();
            $table->string('income')->change();
        });
    }
};
