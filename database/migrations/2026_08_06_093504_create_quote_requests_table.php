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
        // Historical duplicate: the preceding migration owns this table.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // The preceding migration also owns rollback.
    }
};
