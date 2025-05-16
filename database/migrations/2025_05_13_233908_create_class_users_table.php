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
        Schema::create('class_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Внешний ключ на таблицу users
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade'); // Внешний ключ на таблицу classes
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');  // Статус записи (ожидает подтверждения, одобрен, отклонен)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_user');
    }
};
