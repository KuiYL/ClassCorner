<?php

namespace App\Http\Controllers;

use App\Models\Assignments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAssignmentsController extends Controller
{
    public function submitAnswer(Request $request, $id)
    {
        try {
            $assignment = Assignments::findOrFail($id);
            $answers = $request->input('answers', []); // Получаем ответы

            if (empty($answers)) {
                return back()->withErrors(['answers' => 'Необходимо заполнить хотя бы одно поле.']);
            }

            $fields = json_decode($assignment->options, true);
            if (count($answers) !== count($fields)) {
                return back()->withErrors(['answers' => 'Количество ответов должно соответствовать количеству вопросов.']);
            }

            $preparedAnswers = [];

            foreach ($answers as $index => $answer) {
                $field = $fields[$index] ?? null;

                if (!$field) {
                    return back()->withErrors(["answers.{$index}" => 'Неверный индекс ответа.']);
                }

                switch ($field['type']) {
                    case 'text':
                        if (empty(trim($answer['value'] ?? ''))) {
                            return back()->withErrors(["answers.{$index}" => "Поле \"{$field['name']}\" не может быть пустым."]);
                        }
                        $preparedAnswers[] = [
                            'type' => 'text',
                            'value' => $answer['value'],
                        ];
                        break;

                    case 'file_upload':
                        if (!$request->hasFile("answers.$index.file")) {
                            return back()->withErrors(["answers.{$index}" => "Файл обязателен для поля \"{$field['name']}\""]);
                        }
                        $file = $request->file("answers.$index.file");
                        $path = $file->store("assignments/{$assignment->id}", 'public');
                        $preparedAnswers[] = [
                            'type' => 'file_upload',
                            'file_name' => $file->hashName(),
                            'file_path' => $path,
                        ];
                        break;

                    case 'single_choice':
                    case 'multiple_choice':
                        $selectedOptions = $answer['options'] ?? [];
                        if (empty($selectedOptions)) {
                            return back()->withErrors(["answers.{$index}" => "Выберите хотя бы один вариант для \"{$field['name']}\""]);
                        }
                        $preparedAnswers[] = [
                            'type' => $field['type'],
                            'selected_options' => $selectedOptions,
                        ];
                        break;

                    default:
                        return back()->withErrors(["answers.{$index}" => "Некорректный тип поля \"{$field['name']}\""]);
                }
            }

            $user = User::findOrFail(auth()->id());

            $user->studentAssignments()->updateOrCreate(
                ['assignment_id' => $id],
                [
                    'status' => 'submitted',
                    'student_answer' => json_encode($preparedAnswers),
                    'file_path' => $preparedAnswers[0]['file_path'] ?? null,
                ]
            );

            return redirect()->route('class.show', $assignment->class_id)->with('success', 'Ответ успешно отправлен!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


}
