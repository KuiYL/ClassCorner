<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\StudentAssignments;
use App\Models\User;
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
        return view('pages.site.about');
    }

    public function  showCompanyCareerPage()
    {
        return view('pages.site.career');
    }

    public function  showPrivacyPage()
    {
        return view('pages.site.privacy_policy');
    }

    public function  showSecurityPage()
    {
        return view('pages.site.security');
    }

    public function  showServiceConditionsPage()
    {
        return view('pages.site.service_conditions');
    }

    public function  showContactPage()
    {
        return view('pages.site.contact');
    }

    public function  showChoiceLoginPage()
    {
        return view('pages.site.choiceLogin');
    }

    public function  showLoginPage()
    {
        return view('pages.site.login');
    }

    public function showRegisterPage($role)
    {
        $roles = ['student', 'teacher'];

        if (!in_array($role, $roles)) {
            abort(404);
        }
        return view('pages.site.register', compact('role'));
    }

    public function showAvatarChoose()
    {
        $userId = Cache::get('registered_user_id_' . session('registered_user_id'));

        if (!$userId) {
            return redirect()->route('login')->withErrors('Сессия истекла. Повторите регистрацию.');
        }

        $userId = session('registered_user_id');

        if (!$userId) {
            return redirect()->route('login')->withErrors('Сессия истекла. Повторите регистрацию.');
        }

        return view('pages.site.chooseAvatar');
    }

    public function  showDashboradPage()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role === 'teacher') {
            return $this->showDashboardTeacher();
        } else if ($user->role === 'admin') {
            return view('pages.platform.dashboardAdmin', compact('user'));
        } else if ($user->role === 'student') {
            return view('pages.platform.dashboardUser', compact('user'));
        }
    }

    public function showDashboardTeacher()
    {
        $user = User::findOrFail(auth()->id());

        if ($user->role !== 'teacher') {
            abort(403, 'Access denied');
        }

        $classes = $user->classes()->wherePivot('status', 'approved')->get();

        $assignmentsToGrade = StudentAssignments::whereHas('assignment', function ($query) use ($user) {
            $query->where('teacher_id', $user->id);
        })->where('status', 'submitted')->get();

        $activeClassesCount = $user->classes()->wherePivot('status', 'approved')->count();

        // Подсчёт студентов без учителей
        $studentsCount = $user->classes()
            ->wherePivot('status', 'approved')
            ->with(['students' => function ($query) {
                $query->where('role', '!=', 'teacher'); // Исключаем учителей
            }])
            ->get()
            ->sum(fn($class) => $class->students->count());

        $assignmentsCount = StudentAssignments::whereHas('assignment', function ($query) use ($user) {
            $query->where('teacher_id', $user->id);
        })->count();

        $newAssignmentsCount = $assignmentsToGrade->count();

        return view('pages.platform.dashboardTeacher', compact(
            'user',
            'classes',
            'assignmentsToGrade',
            'activeClassesCount',
            'studentsCount',
            'assignmentsCount',
            'newAssignmentsCount'
        ));
    }


    public function  showClassesPage()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role === 'teacher') {
            $classes = $user->classes;

            return view('pages.platform.classesTeacher', compact('user', 'classes'));
        } else if ($user->role === 'admin') {
            return view('pages.platform.dashboardAdmin', compact('user'));
        } else if ($user->role === 'student') {
            return view('pages.platform.dashboardUser', compact('user'));
        }
    }

    public function createClass()
    {
        $teachers = User::where('role', 'teacher')->get();

        return view('pages.platform.classes.create', compact('teachers'));
    }
    public function createAssignments()
    {
        return view('pages.platform.assignments.create');
    }
    public function editClass($id)
    {
        $class = Classes::findOrFail($id);

        return view('pages.platform.classes.update', compact('class'));
    }

    public function showClassPage($classId)
    {
        $user = User::findOrFail(auth()->id());

        if (!$user) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему.');
        }
        $classes = $user->classes;

        $class = $user->classes()->findOrFail($classId);

        if (!$class) {
            return abort(403, 'У вас нет доступа к этому классу.');
        }


        $role = $user->role;
        $assignments = [];
        $students = [];

        if ($role === 'teacher') {
            $assignments = $class->assignments()->where('teacher_id', $user->id)->get();
            $students = $class->students;
        } elseif ($role === 'student') {
            $assignments = $user->studentAssignments()->whereHas('assignment', function ($query) use ($classId) {
                $query->where('class_id', $classId);
            })->get();
        } elseif ($role === 'admin') {
            $assignments = $class->assignments;
            $students = $class->students;
        }

        return view('pages.platform.classes.class', compact('class', 'assignments', 'students', 'role', 'user', 'classes'));
    }
}
