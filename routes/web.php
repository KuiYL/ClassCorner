<?php

use App\Http\Controllers\AssignmentsController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\StudentAssignmentsController;
use App\Http\Controllers\UserController;
use App\Models\StudentAssignments;
use Illuminate\Support\Facades\Route;

// Публичные маршруты
Route::get('/', [PagesController::class, "showHomePage"])->name('home');
Route::get('/about', [PagesController::class, "showAboutCompanyPage"])->name('about');
Route::get('/career', [PagesController::class, "showCompanyCareerPage"])->name('career');
Route::get('/contact', [PagesController::class, "showContactPage"])->name('contact');

Route::get('/privacy-policy', [PagesController::class, "showPrivacyPage"])->name('privacy-policy');
Route::get('/security', [PagesController::class, "showSecurityPage"])->name('security');
Route::get('/service-conditions', [PagesController::class, "showServiceConditionsPage"])->name('service-conditions');

// Авторизация и регистрация
Route::get('/choiceLogin', [PagesController::class, "showChoiceLoginPage"])->name('choiceLogin');
Route::get('/login', [PagesController::class, "showLoginPage"])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.store');

Route::get('/registration/{role}', [PagesController::class, "showRegisterPage"])->name('register.role');
Route::post('/registration/{role}', [UserController::class, 'register'])->name('register.store');

Route::get('/choose-avatar', [PagesController::class, 'showAvatarChoose'])->name('choose.avatar');
Route::post('/choose-avatar', [AvatarController::class, 'store'])->name('choose.avatar.store');

// Маршруты с защитой middleware
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PagesController::class, 'showDashboradPage'])->name('user.dashboard');
    Route::get('/dashboard/classes', [PagesController::class, 'showClassesPage'])->name('user.classes');
    Route::get('/dashboard/assignments', [PagesController::class, 'showAssignmentsPage'])->name('user.assignments');
    Route::get('/dashboard/calendar', [PagesController::class, 'showCalendarPage'])->name('user.calendar');
    Route::get('/dashboard/statistics', [PagesController::class, 'showStatisticsPage'])->name('user.statistics');
    Route::get('/user/profile', [PagesController::class, 'showProfilePage'])->name('user.profile');
    Route::get('/user/choose-avatar', [PagesController::class, 'showEditAvatarChoosePage'])->name('user.choose-avatar');
    Route::post('/user/choose-avatar', [AvatarController::class, 'storeWithID'])->name('user.avatar');

    Route::get('/classes/create', [PagesController::class, 'createClass'])->name('classes.create');
    Route::post('/classes', [ClassesController::class, 'store'])->name('classes.store');

    Route::get('/classes/edit/{id}', [PagesController::class, 'editClass'])->name('classes.edit');
    Route::put('/classes/update/{id}', [ClassesController::class, 'update'])->name('classes.update');
    Route::delete('/classes/{id}', [ClassesController::class, 'destroy'])->name('classes.destroy');

    Route::get('/class/{classId}', [PagesController::class, 'showClassPage'])->name('class.show');

    Route::get('/assignments/create/{classId?}', [PagesController::class, 'createAssignments'])->name('assignments.create');
    Route::post('/assignments', [AssignmentsController::class, 'create'])->name('assignment.store');

    Route::get('/assignments/edit/{id}/{classId?}', [PagesController::class, 'editAssignments'])->name('assignments.edit');
    Route::put('/assignments/update/{id}/{classId?}', [AssignmentsController::class, 'update'])->name('assignments.update');
    Route::delete('/assignments/{id}', [AssignmentsController::class, 'destroy'])->name('assignments.destroy');
    Route::get('/assignment/{id}', [PagesController::class, 'showAssignmentPage'])->name('assignments.show');
    Route::post('/assignment/{id}/submit', [StudentAssignmentsController::class, 'submitAnswer'])->name('assignment.submit.answer');
    Route::get('/assignments/to-grade', [PagesController::class, 'showAssignmentsToGrade'])->name('assignments.to.grade');
    Route::get('/assignment/{id}/grade', [PagesController::class, 'gradeForm'])->name('assignment.grade.form');
    Route::put('/assignment/{id}/grade', [StudentAssignmentsController::class, 'submitGrading'])->name('assignment.grade.save');
    Route::get('/result/{id}', [PagesController::class, 'showAssignmentResult'])->name('assignment.result');

    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::put('/user/edit-profile', [UserController::class, 'updateInfo'])->name('user.edit-profile');
    Route::post('/user/change-password', [UserController::class, 'updatePassword'])->name('user.change-password');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    Route::post('/classes/{class}/invite', [UserController::class, 'invite'])->name('classes.invite');
    Route::post('/invitations/{invitation}/accept', [UserController::class, 'accept'])->name('invitations.accept');
    Route::post('/invitations/{invitation}/decline', [UserController::class, 'decline'])->name('invitations.decline');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [PagesController::class, 'adminHome'])->name('admin.dashboard');
    Route::get('/admin/users', [PagesController::class, 'adminUsers'])->name('admin.users');
    Route::get('/admin/classes', [PagesController::class, 'adminClasses'])->name('admin.classes');
    Route::get('/admin/assignments', [PagesController::class, 'adminAssignments'])->name('admin.assignments');
});

