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
        Schema::create('student_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // Внешний ключ на таблицу users (ученики)
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');  // Внешний ключ на таблицу assignments (задания)
            $table->enum('status', ['not_submitted', 'submitted', 'graded'])->default('not_submitted');  // Статус задания
            $table->string('file_path')->nullable();
            $table->integer('grade')->nullable();  // Оценка
            $table->text('feedback')->nullable();  // Отзыв
            $table->json('student_answer')->nullable();
            $table->timestamp('submitted_at')->nullable();  // Когда студент сдал задание
            $table->boolean('is_late')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_assignments');
    }
};
