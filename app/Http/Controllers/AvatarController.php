<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AvatarController extends Controller
{
    public function store(Request $request)
    {

        $userId = Cache::get('registered_user_id_' . session('registered_user_id'));

        if (!$userId) {
            return redirect()->route('register')->withErrors('Сессия истекла. Повторите регистрацию.');
        }

        $request->validate([
            'avatar' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            'default_avatar' => 'nullable|string',
        ], [
            'avatar.file' => 'Аватар должен быть файлом.',
            'avatar.mimes' => 'Аватар должен быть в формате jpg, png или jpeg.',
            'avatar.max' => 'Размер аватара не должен превышать 2 МБ.',
            'default_avatar.string' => 'Аватар по умолчанию должен быть строкой.',
        ]);

        $user = User::findOrFail($userId);

        if ($request->hasFile('avatar')) {
            $filePath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $filePath;
        } elseif ($request->default_avatar) {
            $user->avatar = 'images/' . $request->default_avatar;
        }

        $user->save();

        Cache::forget('registered_user_id_' . $user->id);

        return redirect()->route('login')->with('status', 'Вы успешно выбрали аватар. Теперь войдите в систему.');
    }

    public function storeWithID(Request $request)
    {

        $user = User::findOrFail(auth()->id());
        if (!$user) {
            return redirect()->route('register')->withErrors('Сессия истекла. Повторите регистрацию.');
        }

        $request->validate([
            'avatar' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            'default_avatar' => 'nullable|string',
        ], [
            'avatar.file' => 'Аватар должен быть файлом.',
            'avatar.mimes' => 'Аватар должен быть в формате jpg, png или jpeg.',
            'avatar.max' => 'Размер аватара не должен превышать 2 МБ.',
            'default_avatar.string' => 'Аватар по умолчанию должен быть строкой.',
        ]);

        if ($request->hasFile('avatar')) {
            $filePath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $filePath;
        } elseif ($request->default_avatar) {
            $user->avatar = 'images/' . $request->default_avatar;
        }

        $user->save();

        return redirect()->route('user.profile');
    }
}
