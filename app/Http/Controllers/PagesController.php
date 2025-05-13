<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PagesController extends Controller
{
    public function  showHomePage()
    {
        return view('pages.home');
    }

    public function  showAboutCompanyPage()
    {
        return view('pages.about');
    }

    public function  showCompanyCareerPage()
    {
        return view('pages.career');
    }

    public function  showPrivacyPage()
    {
        return view('pages.privacy_policy');
    }

    public function  showSecurityPage()
    {
        return view('pages.security');
    }

    public function  showServiceConditionsPage()
    {
        return view('pages.service_conditions');
    }

    public function  showContactPage()
    {
        return view('pages.contact');
    }

    public function  showChoiceLoginPage()
    {
        return view('pages.choiceLogin');
    }

    public function  showLoginPage()
    {
        return view('pages.login');
    }

    public function showRegisterPage($role)
    {
        $roles = ['student', 'teacher'];

        if (!in_array($role, $roles)) {
            abort(404);
        }
        return view('pages.register', compact('role'));
    }

    public function showAvatarChoose()
    {
        $userId = Cache::get('registered_user_id_' . session('registered_user_id'));

        if (!$userId) {
            Log::info('User ID not found in cache or session.');
            return redirect()->route('login')->withErrors('Сессия истекла. Повторите регистрацию.');
        }

        $userId = session('registered_user_id');

        if (!$userId) {
            return redirect()->route('login')->withErrors('Сессия истекла. Повторите регистрацию.');
        }
        Log::info('Redirecting to Avatar Choice for User ID: ' . $userId);

        return view('pages.chooseAvatar');
    }
}
