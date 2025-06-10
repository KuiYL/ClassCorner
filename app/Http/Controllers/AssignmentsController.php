<?php

namespace App\Http\Controllers;

use App\Models\AssignmentMaterial;
use App\Models\Assignments;
use App\Models\ClassUser;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentsController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'class_id' => 'required|exists:classes,id',
                'description' => 'nullable|string',
                'due_date' => 'required|date|after_or_equal:today',
                'due_time' => 'required',
                'fields_json' => 'nullable|json',
                'materials' => 'nullable|array',
                'materials.*' => 'file|mimes:pdf,jpeg,jpg,png,doc,docx,xls,xlsx,csv,ppt,pptx,txt,zip,rar|max:10240'
            ], [
                'title.required' => 'Поле "Название задания" обязательно для заполнения.',
                'title.string' => 'Поле "Название задания" должно быть строкой.',
                'title.max' => 'Поле "Название задания" не должно превышать 255 символов.',
                'class_id.required' => 'Поле "Класс" обязательно для заполнения.',
                'class_id.exists' => 'Выбранный класс не существует.',
                'description.string' => 'Поле "Описание" должно быть строкой.',
                'due_date.required' => 'Поле "Дата сдачи" обязательно для заполнения.',
                'due_date.date' => 'Поле "Дата сдачи" должно быть корректной датой.',
                'due_date.after_or_equal' => 'Дата сдачи не может быть раньше сегодняшней даты.',
                'due_time.required' => 'Поле "Время сдачи" обязательно для заполнения.',
                'fields_json.json' => 'Ошибка в структуре данных полей задания.',
            ]);
            $dueDateTime = $request->input('due_date') . ' ' . $request->input('due_time');
            try {
                $dueDateTime = new \DateTime($dueDateTime);
            } catch (\Exception $e) {
                return back()
                    ->withErrors(['due_time' => 'Некорректное значение времени.'])
                    ->withInput($request->all());
            }
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
                'due_date' => $dueDateTime,
                'teacher_id' => $user->role == 'teacher' ? $user->id : null,
                'class_id' => $request->input('class_id'),
                'options' => json_encode($fields),
            ]);

            if ($request->hasFile('materials')) {
                foreach ($request->file('materials') as $file) {
                    $filename = $file->getClientOriginalName();
                    $filePath = $file->storeAs('assignments/' . $assignment->id, $filename, 'public');

                    AssignmentMaterial::create([
                        'assignment_id' => $assignment->id,
                        'file_name' => $filename,
                        'file_path' => $filePath,
                    ]);
                }
            }

            $classUsers = ClassUser::with('user')
                ->where('class_id', $request->input('class_id'))
                ->get();
            foreach ($classUsers as $student) {
                $this->createNotification(
                    $student->id,
                    'Новое задание',
                    'Задание "' . $assignment->title . '" добавлено.',
                    'assignment_created'
                );
            }

            $returnUrl = $request->input('return_url', route('user.assignments'));
            return redirect($returnUrl)->with('success', 'Задание успешно создано!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->all());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'class_id' => 'required|exists:classes,id',
                'description' => 'nullable|string',
                'due_date' => 'required|date|after_or_equal:today',
                'due_time' => 'required',
                'fields_json' => 'nullable|json',
                'materials' => 'nullable|array',
                'materials.*' => 'file|mimes:pdf,jpeg,jpg,png,doc,docx,xls,xlsx,csv,ppt,pptx,txt,zip,rar|max:10240'
            ], [
                'title.required' => 'Поле "Название задания" обязательно для заполнения.',
                'title.string' => 'Поле "Название задания" должно быть строкой.',
                'title.max' => 'Поле "Название задания" не должно превышать 255 символов.',
                'class_id.required' => 'Поле "Класс" обязательно для заполнения.',
                'class_id.exists' => 'Выбранный класс не существует.',
                'description.string' => 'Поле "Описание" должно быть строкой.',
                'due_date.required' => 'Поле "Дата сдачи" обязательно для заполнения.',
                'due_date.date' => 'Поле "Дата сдачи" должно быть корректной датой.',
                'due_date.after_or_equal' => 'Дата сдачи не может быть раньше сегодняшней даты.',
                'due_time.required' => 'Поле "Время сдачи" обязательно для заполнения.',
                'fields_json.json' => 'Ошибка в структуре данных полей задания.',
            ]);
            $dueDateTime = $request->input('due_date') . ' ' . $request->input('due_time');
            try {
                $dueDateTime = new \DateTime($dueDateTime);
            } catch (\Exception $e) {
                return back()
                    ->withErrors(['due_time' => 'Некорректное значение времени.'])
                    ->withInput($request->all());
            }
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
                'due_date' => $dueDateTime,
                'class_id' => $request->input('class_id'),
                'options' => json_encode($fields),
            ]);

            if ($request->hasFile('materials')) {
                foreach ($request->file('materials') as $file) {
                    $filename = $file->getClientOriginalName();
                    $filePath = $file->storeAs('assignments/' . $assignment->id, $filename, 'public');

                    AssignmentMaterial::create([
                        'assignment_id' => $assignment->id,
                        'file_name' => $filename,
                        'file_path' => $filePath,
                    ]);
                }
            }
            $returnUrl = $request->input('return_url', route('user.assignments'));
            return redirect($returnUrl)->with('success', 'Задание успешно обновлено!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput($request->all());;
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $assignment = Assignments::findOrFail($id);
        $assignment->delete();

        $returnUrl = request('return_url') ?: url()->previous();

        return redirect()->to($returnUrl)->with('success', 'Задание успешно удалено.');
    }

    public function deleteMaterial($id)
    {
        $material = AssignmentMaterial::findOrFail($id);
        Storage::disk('public')->delete($material->file_path);
        $material->delete();

        return redirect()->back()->with('success', 'Материал успешно удалено.');
    }

    public function createNotification($userId, $title, $message, $type = 'general')
    {
        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ]);
    }
}
