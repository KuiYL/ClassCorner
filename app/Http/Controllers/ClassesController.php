<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher_id' => 'required|exists:users,id',
        ]);

        $class = new Classes();
        $class->name = $validated['name'];
        $class->description = $validated['description'];
        $class->teacher_id = $validated['teacher_id'];
        $class->save();
        $class->students()->attach($validated['teacher_id'], ['status' => 'approved']);
        return redirect()->route('user.classes')->with('success', 'Класс успешно создан');
    }

    public function update(Request $request, $id)
    {
        $class = Classes::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $class->update($validated);

        return redirect()->route('user.classes')->with('success', 'Класс успешно обновлен.');
    }

    public function destroy($id)
    {
        $class = Classes::findOrFail($id);
        $class->delete();

        return redirect()->route('user.classes')->with('success', 'Класс успешно удален.');
    }
}
