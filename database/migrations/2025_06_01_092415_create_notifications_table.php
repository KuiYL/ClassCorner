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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->enum('type', [
                'assignment_submitted',   // Задание отправлено на проверку
                'assignment_graded',      // Задание проверено, выставлена оценка
                'class_joined',           // Ученик присоединился к классу
                'class_invitation',       // Получено приглашение в класс
                'assignment_created',     // Новое задание добавлено
                'assignment_reminder',    // Напоминание о дедлайне
                'feedback_received',      // Получен комментарий от преподавателя
                'general'                 // Общее уведомление
            ])->default('general');
            $table->boolean('read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
