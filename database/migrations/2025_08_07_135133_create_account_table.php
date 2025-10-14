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
    Schema::create('account', function (Blueprint $table) {
    $table->id();
    $table->string('email_account')->unique();
    $table->string('password_account');
    $table->integer('login_count_account')->default(0);
    $table->boolean('lock_account')->default(false);
    $table->dateTime('ban_account')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account');
    }
};
