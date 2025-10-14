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
      Schema::table('courses', function (Blueprint $table) {
            $table->timestamps(); // Adds both created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('courses', function (Blueprint $table) {
            $table->dropTimestamps(); // Rolls back the change
        });
    }
};
