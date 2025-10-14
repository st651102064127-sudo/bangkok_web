<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->bigIncrements('enroll_id'); // Primary key
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('payment_id')->nullable(); // อ้างอิงจากระบบชำระเงิน
            $table->decimal('payment_amount', 10, 2)->nullable(); // จำนวนเงินที่จ่าย
            $table->enum('status', ['pending', 'paid', 'cancelled', 'completed'])->default('pending');
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
