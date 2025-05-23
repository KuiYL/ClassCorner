<?php

namespace App\Http\Controllers;

use App\Models\Assignments;
use Illuminate\Http\Request;

class AssignmentsController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'due_date' => 'required|date',
                'class_id' => 'required|exists:classes,id',
            ], [
                'title.required' => 'Название задания обязательно для заполнения.',
                'description.required' => 'Описание задания обязательно для заполнения.',
                'due_date.required' => 'Дата сдачи задания обязательна для заполнения.',
                'due_date.date' => 'Дата сдачи должна быть корректной датой.',
                'class_id.required' => 'Выберите класс для задания.',
                'class_id.exists' => 'Выбранный класс не существует.',
            ]);

            $fields = json_decode($request->input('fields_json'), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Ошибка декодирования JSON для полей задания.');
            }

            foreach ($fields as $field) {
                if (empty($field['name']) || empty($field['type'])) {
                    throw new \Exception('Одно из полей задания имеет некорректный формат.');
                }

                if (!in_array($field['type'], ['file_upload', 'multiple_choice', 'single_choice', 'text'])) {
                    throw new \Exception('Некорректный тип поля задания.');
                }

                if (in_array($field['type'], ['multiple_choice', 'single_choice'])) {
                    foreach ($field['options'] ?? [] as $option) {
                        if (!isset($option['value']) || empty($option['value'])) {
                            throw new \Exception('Некорректный вариант ответа в одном из полей.');
                        }
                    }
                }
            }

            $user = auth()->user();
            $assignment = Assignments::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'due_date' => $validated['due_date'],
                'teacher_id' => $user->role == 'teacher' ? $user->id : null,
                'class_id' => $request->input('class_id'),
                'options' => json_encode($fields),
                'status' => 'pending',
            ]);
            $returnUrl = $request->input('return_url', route('user.assignments'));
            return redirect($returnUrl)->with('success', 'Задание успешно создано!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'due_date' => 'required|date',
                'class_id' => 'required|exists:classes,id',
            ], [
                'title.required' => 'Название задания обязательно для заполнения.',
                'description.required' => 'Описание задания обязательно для заполнения.',
                'due_date.required' => 'Дата сдачи задания обязательна для заполнения.',
                'due_date.date' => 'Дата сдачи должна быть корректной датой.',
                'class_id.required' => 'Выберите класс для задания.',
                'class_id.exists' => 'Выбранный класс не существует.',
            ]);

            $fields = json_decode($request->input('fields_json'), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Ошибка декодирования JSON для полей задания.');
            }

            foreach ($fields as $field) {
                if (empty($field['name']) || empty($field['type'])) {
                    throw new \Exception('Одно из полей задания имеет некорректный формат.');
                }

                if (!in_array($field['type'], ['file_upload', 'multiple_choice', 'single_choice', 'text'])) {
                    throw new \Exception('Некорректный тип поля задания.');
                }

                if (in_array($field['type'], ['multiple_choice', 'single_choice'])) {
                    foreach ($field['options'] ?? [] as $option) {
                        if (!isset($option['value']) || empty($option['value'])) {
                            throw new \Exception('Некорректный вариант ответа в одном из полей.');
                        }
                    }
                }
            }

            $assignment = Assignments::findOrFail($id);

            $assignment->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'due_date' => $validated['due_date'],
                'class_id' => $request->input('class_id'),
                'options' => json_encode($fields),
            ]);
            $returnUrl = $request->input('return_url', route('user.assignments'));
            return redirect($returnUrl)->with('success', 'Задание успешно обновлено!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function destroy($id)
    {
        $assignment = Assignments::findOrFail($id);
        $assignment->delete();

        return redirect()->back()->with('success', 'Задание успешно удалено.');
    }
}
