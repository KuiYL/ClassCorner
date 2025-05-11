<?php

use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PagesController::class, "showHomePage"])->name('home');
Route::get('/about', [PagesController::class, "showAboutCompanyPage"])->name('about');
Route::get('/career', [PagesController::class, "showCompanyCareerPage"])->name('career');
Route::get('/contact', [PagesController::class, "showContactPage"])->name('contact');

Route::get('/privacy-policy', [PagesController::class, "showPrivacyPage"])->name('privacy-policy');
Route::get('/security', [PagesController::class, "showSecurityPage"])->name('security');
Route::get('/service-conditions', [PagesController::class, "showServiceConditionsPage"])->name('service-conditions');
