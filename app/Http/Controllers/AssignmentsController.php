<?php

namespace App\Http\Controllers;

use App\Models\Assignments;
use Illuminate\Http\Request;

class AssignmentsController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'due_date' => 'required|date',
                'fields' => 'required|array',
                'fields.*.name' => 'required|string',
                'fields.*.type' => 'required|string',
                'fields.*.options' => 'nullable|array',
                'fields.*.options.*.value' => 'required_with:fields.*.options|string',
                'fields.*.options.*.isCorrect' => 'boolean',
            ]);

            $assignment = Assignments::create($validated);

            foreach ($validated['fields'] as $field) {
                $assignment->fields()->create($field);
            }

            return redirect()->route('user.classes')->with('success', 'Задание успешно создано!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
