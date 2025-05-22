<?php

namespace App\Http\Controllers;

use App\Models\Assignments;
use App\Models\Classes;
use App\Models\StudentAssignments;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PagesController extends Controller
{

    private function getAuthenticatedUser()
    {
        $user = User::findOrFail(auth()->id());
        if (!$user) {
            return redirect()->route('login');
        }
        return $user;
    }

    private function redirectIfNotRole($user, $role)
    {
        if ($user->role !== $role) {
            abort(403, 'Access denied');
        }
    }

    private function getUserClasses($user, $status = 'approved')
    {
        return $user->classes()->wherePivot('status', $status)->get();
    }

    private function getTeacherStudentsCount($user)
    {
        $classes = $this->getUserClasses($user);

        return $classes->sum(
            fn($class) =>
            $class->students->filter(fn($student) => $student->role === 'student')->count()
        );
    }

    private function getUserAssignmentsToGrade($user)
    {
        return StudentAssignments::whereHas('assignment', function ($query) use ($user) {
            $query->where('teacher_id', $user->id);
        })->where('status', 'submitted')->get();
    }

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
        $userId = session('registered_user_id');
        if (!$userId || !Cache::has('registered_user_id_' . $userId)) {
            return redirect()->route('login')->withErrors('Сессия истекла. Повторите регистрацию.');
        }
        return view('pages.site.chooseAvatar');
    }

    public function showDashboradPage()
    {
        $user = $this->getAuthenticatedUser();

        switch ($user->role) {
            case 'teacher':
                return $this->showDashboardTeacher($user);
            case 'admin':
                return view('pages.platform.dashboardAdmin', compact('user'));
            case 'student':
                return view('pages.platform.dashboardUser', compact('user'));
        }
    }

    public function showDashboardTeacher($user = null)
    {
        $user = $user ?? $this->getAuthenticatedUser();
        $this->redirectIfNotRole($user, 'teacher');

        $classes = $this->getUserClasses($user);
        $assignmentsToGrade = $this->getUserAssignmentsToGrade($user);
        $teacherStudentsCount = $this->getTeacherStudentsCount($user);
        return view('pages.platform.dashboardTeacher', [
            'user' => $user,
            'classes' => $classes,
            'assignmentsToGrade' => $assignmentsToGrade,
            'activeClassesCount' => $classes->count(),
            'studentsCount' => $teacherStudentsCount,
            'assignmentsCount' => $assignmentsToGrade->count(),
            'newAssignmentsCount' => $assignmentsToGrade->count(),
        ]);
    }

    public function showClassesPage()
    {
        $user = $this->getAuthenticatedUser();

        switch ($user->role) {
            case 'teacher':
                $classes = $this->getUserClasses($user);
                return view('pages.platform.classesTeacher', compact('user', 'classes'));
            case 'admin':
                return view('pages.platform.dashboardAdmin', compact('user'));
            case 'student':
                return view('pages.platform.dashboardUser', compact('user'));
        }
    }

    public function showAssignmentsPage()
    {
        $user = $this->getAuthenticatedUser();

        switch ($user->role) {
            case 'teacher':
                $classes = $this->getUserClasses($user);
                $assignments = Assignments::where('teacher_id', $user->id)->get();

                return view('pages.platform.assignmentsTeacher', compact('user', 'classes', 'assignments'));
            case 'admin':
                return view('pages.platform.dashboardAdmin', compact('user'));
            case 'student':
                return view('pages.platform.dashboardUser', compact('user'));
        }
    }

    public function showCalendarPage()
    {
        $user = $this->getAuthenticatedUser();

        switch ($user->role) {
            case 'teacher':
                $classes = $this->getUserClasses($user);

                $assignments = Assignments::where('teacher_id', $user->id)
                    ->with('class')
                    ->orderBy('due_date')
                    ->get()
                    ->groupBy('due_date')
                    ->map(function ($group) {
                        return $group->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'title' => $item->title,
                                'description' => $item->description,
                                'due_date' => $item->due_date,
                                'class_id' => $item->class_id,
                                'class_name' => optional($item->class)->name,
                            ];
                        });
                    });

                return view('pages.platform.calendarTeacher', compact('user', 'classes', 'assignments'));

            case 'admin':
                return view('pages.platform.dashboardAdmin', compact('user'));

            case 'student':
                return view('pages.platform.dashboardUser', compact('user'));
        }
    }

    public function showStatisticsPage()
    {
        $user = $this->getAuthenticatedUser();

        switch ($user->role) {
            case 'teacher':
                $classes = $this->getUserClasses($user);
                $assignments = Assignments::where('teacher_id', $user->id)->get();

                return view('pages.platform.statisticsTeacher', compact('user', 'classes', 'assignments'));
            case 'admin':
                return view('pages.platform.dashboardAdmin', compact('user'));
            case 'student':
                return view('pages.platform.dashboardUser', compact('user'));
        }
    }

    public function showProfilePage()
    {
        $user = $this->getAuthenticatedUser();

        switch ($user->role) {
            case 'teacher':
                $classes = $this->getUserClasses($user);
                $assignments = Assignments::where('teacher_id', $user->id)->get();

                return view('pages.user.profile', compact('user', 'classes'));
            case 'admin':
                return view('pages.platform.dashboardAdmin', compact('user'));
            case 'student':
                return view('pages.platform.dashboardUser', compact('user'));
        }
    }

    public function showEditAvatarChoosePage()
    {
        $user = $this->getAuthenticatedUser();

        switch ($user->role) {
            case 'teacher':
                $classes = $this->getUserClasses($user);
                $assignments = Assignments::where('teacher_id', $user->id)->get();

                return view('pages.user.chooseAvatar', compact('user', 'classes'));
            case 'admin':
                return view('pages.platform.dashboardAdmin', compact('user'));
            case 'student':
                return view('pages.platform.dashboardUser', compact('user'));
        }
    }

    public function createClass()
    {
        $user = $this->getAuthenticatedUser();
        $teachers = User::where('role', 'teacher')->get();
        $classes = $this->getUserClasses($user);

        return view('pages.classes.create', compact('teachers', 'user', 'classes'));
    }

    public function createAssignments($classId = null)
    {
        $user = $this->getAuthenticatedUser();
        $classes = $this->getUserClasses($user);

        $selectedClass = $classId ? $classes->find($classId) : null;
        if ($classId && !$selectedClass) {
            return redirect()->route('assignments.create')->withErrors('Класс не найден или недоступен.');
        }

        return view('pages.assignments.create', compact('user', 'classes', 'selectedClass'));
    }

    public function editAssignments($id, $classId = null)
    {
        $user = $this->getAuthenticatedUser();
        $classes = $this->getUserClasses($user);
        $assignment = Assignments::findOrFail($id);
        $fields = json_decode($assignment->options, true) ?? [];
        $selectedClass = $classId ? $classes->find($classId) : null;
        if ($classId && !$selectedClass) {
            return redirect()->route('assignments.create')->withErrors('Класс не найден или недоступен.');
        }

        return view('pages.assignments.update', compact('user', 'classes', 'selectedClass', 'assignment', 'fields'));
    }

    public function editClass($id)
    {
        $class = Classes::findOrFail($id);
        $user = $this->getAuthenticatedUser();
        $classes = $this->getUserClasses($user);

        return view('pages.classes.update', compact('class', 'user', 'classes'));
    }

    public function showClassPage($classId)
    {
        $user = $this->getAuthenticatedUser();
        $class = $user->classes()->findOrFail($classId);

        if (!$class) {
            abort(403, 'У вас нет доступа к этому классу.');
        }

        $role = $user->role;

        $assignments = $role === 'teacher'
            ? $class->assignments()->where('teacher_id', $user->id)->get()
            : [];

        $students = $role === 'teacher' || $role === 'admin'
            ? $class->students
            : [];

        $availableStudents = User::query()
            ->where('role', 'student')
            ->whereDoesntHave('classes', function ($query) use ($classId) {
                $query->where('class_id', $classId);
            })
            ->get();

        $classes = $this->getUserClasses($user);

        return view('pages.classes.class', compact(
            'class',
            'assignments',
            'students',
            'role',
            'user',
            'classes',
            'availableStudents'
        ));
    }

    public function showAssignmentPage($id)
    {
        $user = $this->getAuthenticatedUser();
        $classes = $this->getUserClasses($user);
        $assignment = Assignments::with('class')->findOrFail($id);
        $assignmentFields = json_decode($assignment->options, true);
        return view('pages.assignments.assignment', compact('user', 'classes', 'assignment', 'assignmentFields'));
    }
}
