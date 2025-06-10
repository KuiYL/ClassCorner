<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
                'teacher_id' => 'required|exists:users,id',
            ],
            [
                'name.required' => 'Поле "Название" обязательно для заполнения.',
                'name.string' => 'Поле "Название" должно быть строкой.',
                'name.max' => 'Поле "Название" не может быть длиннее 100 символов.',
                'description.string' => 'Поле "Описание" должно быть строкой.',
                'teacher_id.required' => 'Поле "Преподаватель" обязательно для заполнения.',
                'teacher_id.exists' => 'Выбранное значение для "Преподаватель" некорректно.',
            ]
        );

        $class = new Classes();
        $class->name = $validated['name'];
        $class->description = $validated['description'];
        $class->teacher_id = $validated['teacher_id'];
        $class->save();
        $class->students()->attach($validated['teacher_id'], ['status' => 'approved']);

        $returnUrl = $request->input('return_url', route('user.classes'));
        return redirect($returnUrl)->with('success', 'Класс успешно создан');
    }

    public function update(Request $request, $id)
    {
        $class = Classes::findOrFail($id);
        $validated = $request->validate(
            [
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
            ],
            [
                'name.required' => 'Поле "Название" обязательно для заполнения.',
                'name.string' => 'Поле "Название" должно быть строкой.',
                'name.max' => 'Поле "Название" не может быть длиннее 100 символов.',
                'description.string' => 'Поле "Описание" должно быть строкой.',
            ]
        );

        $class->update($validated);

        $returnUrl = $request->input('return_url', route('user.classes'));
        return redirect($returnUrl)->with('success', 'Класс успешно обновлен.');
    }

    public function destroy($id)
    {
        $class = Classes::findOrFail($id);
        $class->delete();
        $returnUrl = request('return_url') ?: url()->previous();

        return redirect()->to($returnUrl)->with('success', 'Класс успешно удален.');
    }
}
