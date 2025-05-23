<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

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


    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Поле "Текущий пароль" обязательно для заполнения.',
            'password.required' => 'Поле "Новый пароль" обязательно для заполнения.',
            'password.min' => 'Поле "Новый пароль" должно содержать не менее 6 символов.',
            'password.confirmed' => 'Поле "Новый пароль" и "Подтверждение пароля" не совпадают.',
        ]);

        $user = User::findOrFail(auth()->id());

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->route('user.profile')
                ->withErrors(['current_password' => 'Текущий пароль неверен.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()->route('user.profile')
            ->with('success', 'Пароль изменён.');
    }

    public function updateInfo(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
        ], [
            'name.required' => 'Поле "Имя" обязательно для заполнения.',
            'name.max' => 'Поле "Имя" должно содержать не более 255 символов.',
            'surname.required' => 'Поле "Фамилия" обязательно для заполнения.',
            'surname.max' => 'Поле "Фамилия" должно содержать не более 255 символов.',
            'email.required' => 'Поле "Email" обязательно для заполнения.',
            'email.email' => 'Поле "Email" должно быть корректным адресом электронной почты.',
            'email.max' => 'Поле "Email" должно содержать не более 255 символов.',
            'email.unique' => 'Такой Email уже зарегистрирован.',
        ]);

        $user = User::findOrFail(auth()->id());
        $user->update([
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'email' => $request->input('email'),
        ]);

        return redirect()->route('user.profile')
            ->with('success', 'Информация обновлена.');
    }


    public function destroy($userId)
    {
        $currentUser = auth()->user();

        $user = User::findOrFail($userId);

        if ($currentUser->id !== $user->id && $currentUser->role !== 'admin') {
            return redirect()->back()->withErrors(['error' => 'У вас нет прав для удаления данного пользователя.']);
        }

        try {
            $user->delete();

            if ($currentUser->id === $user->id) {
                auth()->logout();
                request()->session()->invalidate();
                request()->session()->regenerateToken();

                return redirect()->route('home')->with('success', 'Ваш аккаунт успешно удалён.');
            }

            return redirect()->route('user.list')->with('success', 'Пользователь успешно удалён.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ошибка при удалении пользователя.']);
        }
    }

    public function invite(Request $request, $classId)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        Invitation::create([
            'class_id' => $classId,
            'inviter_id' => auth()->id(),
            'invitee_email' => $request->email,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Приглашение успешно отправлено!');
    }

    public function accept($invitationId)
    {
        $invitation = Invitation::findOrFail($invitationId);
        $invitation->update(['status' => 'accepted']);

        $class = $invitation->class;
        $user = User::where('email', $invitation->invitee_email)->first();
        $class->students()->attach($user->id, ['status' => 'approved']);
        return back()->with('success', 'Приглашение принято!');
    }

    public function decline($invitationId)
    {
        $invitation = Invitation::findOrFail($invitationId);
        $invitation->update(['status' => 'declined']);
        return back()->with('error', 'Приглашение отклонено!');
    }
}
