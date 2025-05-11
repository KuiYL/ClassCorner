<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('pages.service-conditions');
    }
    public function  showContactPage()
    {
        return view('pages.contact');
    }
}
