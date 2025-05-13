<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function register(Request $request, $role)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[А-Яа-яЁёA-Za-z\s\-]+$/u',
            ],
            'surname' => [
                'required',
                'string',
                'max:255',
                'regex:/^[А-Яа-яЁёA-Za-z\s\-]+$/u',
            ],
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/',
            ],
        ], [
            'name.required' => 'Поле "Имя" обязательно для заполнения.',
            'name.string' => 'Поле "Имя" должно содержать текст.',
            'name.max' => 'Поле "Имя" не может превышать 255 символов.',
            'name.regex' => 'Поле "Имя" может содержать только буквы, пробелы и дефисы.',

            'surname.required' => 'Поле "Фамилия" обязательно для заполнения.',
            'surname.string' => 'Поле "Фамилия" должно содержать текст.',
            'surname.max' => 'Поле "Фамилия" не может превышать 255 символов.',
            'surname.regex' => 'Поле "Фамилия" может содержать только буквы, пробелы и дефисы.',

            'email.required' => 'Поле "Электронная почта" обязательно для заполнения.',
            'email.email' => 'Поле "Электронная почта" должно быть корректным адресом.',
            'email.unique' => 'Этот адрес электронной почты уже зарегистрирован.',

            'password.required' => 'Поле "Пароль" обязательно для заполнения.',
            'password.min' => 'Пароль должен содержать минимум 8 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
            'password.regex' => 'Пароль должен содержать хотя бы одну заглавную букву, одну цифру и один спецсимвол.',
        ]);

        $user = User::create([
            'name' => $validated["name"],
            'surname' => $validated["surname"],
            'email' => $validated["email"],
            'password' => Hash::make($validated["password"]),
            'role' => $role,
        ]);

        Cache::put('registered_user_id_' . $user->id, $user->id, now()->addMinutes(15));
        $request->session()->put('registered_user_id', $user->id);
        return redirect()->route('choose.avatar');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Поле "Электронная почта" обязательно для заполнения.',
            'email.email' => 'Поле "Электронная почта" должно быть корректным адресом.',
            'password.required' => 'Поле "Пароль" обязательно для заполнения.',
            'password.min' => 'Поле "Пароль" должно содержать минимум 6 символов.',
        ]);

        if (auth()->attempt($validated)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return back()->withErrors(['global' => 'Неверные учетные данные'])->withInput();
    }

    public function logout()
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('home');
    }
}
