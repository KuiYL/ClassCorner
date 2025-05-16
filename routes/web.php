<?php

use App\Http\Controllers\AssignmentsController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\UserController;
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

Route::get('/choiceLogin', [PagesController::class, "showChoiceLoginPage"])->name('choiceLogin');
Route::get('/login', [PagesController::class, "showLoginPage"])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.store');

Route::get('/registration/{role}', [PagesController::class, "showRegisterPage"])->name('register.role');
Route::post('/registration/{role}', [UserController::class, 'register'])->name('register.store');

Route::get('/choose-avatar', [PagesController::class, 'showAvatarChoose'])->name('choose.avatar');
Route::post('/choose-avatar', [AvatarController::class, 'store'])->name('choose.avatar.store');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PagesController::class, 'showDashboradPage'])->name('user.dashboard');
    Route::get('/dashboard/classes', [PagesController::class, 'showClassesPage'])->name('user.classes');

    Route::get('/classes/create', [PagesController::class, 'createClass'])->name('classes.create');
    Route::post('/classes', [ClassesController::class, 'store'])->name('classes.store');

    Route::get('/classes/edit/{id}', [PagesController::class, 'editClass'])->name('classes.edit');
    Route::put('/classes/update/{id}', [ClassesController::class, 'update'])->name('classes.update');
    Route::delete('/classes/{id}', [ClassesController::class, 'destroy'])->name('classes.destroy');

    Route::get('/class/{classId}', [PagesController::class, 'showClassPage'])->name('class.show');

    Route::get('/assignments/create', [PagesController::class, 'createAssignments'])->name('assignments.create');
    Route::post('/assignments', [AssignmentsController::class, 'store'])->name('assignment.store');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});
