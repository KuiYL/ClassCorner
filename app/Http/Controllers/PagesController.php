<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function  showHomePage(){
        return view('pages.home');
    }

    public function  showAboutCompanyPage(){
        return view('pages.about');
    }

    public function  showCompanyCareerPage(){
        return view('pages.career');
    }
}
