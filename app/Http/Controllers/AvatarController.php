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
}
