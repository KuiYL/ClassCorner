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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('title');  // Название задания
            $table->text('description');  // Описание задания
            $table->date('due_date');  // Дата сдачи задания
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');  // Внешний ключ на учителя
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');  // Внешний ключ на класс
            $table->enum('type', ['file_upload', 'multiple_choice', 'single_choice', 'text']);  // Тип задания
            $table->json('options')->nullable();  // Дополнительные опции (для выбора вариантов ответа и других настроек)
            $table->enum('status', ['pending', 'active', 'completed'])->default('pending'); // Добавляем статус
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
