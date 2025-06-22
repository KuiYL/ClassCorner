<?php

namespace App\Http\Controllers;

use App\Models\Assignments;
use App\Models\Classes;
use App\Models\Invitation;
use App\Models\StudentAssignments;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
        })->whereIn('status', ['submitted', 'graded'])->get();
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
                return $this->adminHome();
            case 'student':
                return $this->showDashboardUser($user);
            default:
                abort(403, 'Нет доступа');
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
            'newAssignmentsCount' => $assignmentsToGrade->where('status', 'submitted')->count(),
        ]);
    }

    public function showDashboardUser($user = null)
    {
        $user = $user ?? $this->getAuthenticatedUser();
        $this->redirectIfNotRole($user, 'student');

        $classes = $user->classes;

        $assignments = [];

        foreach ($classes as $class) {
            $classAssignments = $class->assignments;

            foreach ($classAssignments as $assignment) {
                $submission = StudentAssignments::where('user_id', $user->id)
                    ->where('assignment_id', $assignment->id)
                    ->first();

                $isCompleted = false;
                if ($submission) {
                    $isCompleted = in_array($submission->status, ['submitted', 'graded']);
                }

                $assignments[] = [
                    'class' => $class,
                    'assignment' => $assignment,
                    'completed' => $isCompleted,
                    'submission' => $submission,
                ];
            }
        }

        $totalClasses = $classes->count();
        $totalAssignments = count($assignments);
        $completedAssignments = count(array_filter($assignments, fn($a) => $a['completed']));

        return view('pages.platform.dashboardUser', compact(
            'user',
            'classes',
            'assignments',
            'totalClasses',
            'totalAssignments',
            'completedAssignments'
        ));
    }

    public function showClassesPage()
    {
        $user = $this->getAuthenticatedUser();

        switch ($user->role) {
            case 'teacher':
                $perPage = 6;
                $filter = request('filter', '');
                $classes = $this->getUserClasses($user);
                $filterClasses = $classes;
                if ($filter !== '') {
                    $filterClasses = $classes->filter(function ($class) use ($filter) {
                        return mb_stripos($class->name, $filter) !== false;
                    })->values();
                }

                $perPage = 6;
                $currentPage = LengthAwarePaginator::resolveCurrentPage();

                $total = $filterClasses->count();

                $itemsForCurrentPage = $filterClasses->slice(($currentPage - 1) * $perPage, $perPage)->values();

                $paginatedItems = new LengthAwarePaginator(
                    $itemsForCurrentPage,
                    $total,
                    $perPage,
                    $currentPage,
                    [
                        'path' => route('user.classes'),
                        'query' => ['filter' => $filter],
                    ]
                );

                return view('pages.platform.classesTeacher', compact('user', 'paginatedItems', 'filter', 'classes'));

            case 'admin':
                $classes = Classes::all();
                $perPage = 6;
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $paginatedItems = new LengthAwarePaginator(
                    $classes->forPage($currentPage, $perPage),
                    $classes->count(),
                    $perPage,
                    $currentPage,
                    ['path' => route('user.classes')]
                );
                return view('pages.platform.dashboardAdmin', compact('user', 'paginatedItems'));

            case 'student':
                $classes = $this->getUserClasses($user);
                $invitations = Invitation::where('invitee_email', $user->email)
                    ->where('status', 'pending')
                    ->with('class.teacher')
                    ->get();

                $perPage = 6;
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $paginatedItems = new LengthAwarePaginator(
                    Collection::make($classes)->forPage($currentPage, $perPage),
                    count($classes),
                    $perPage,
                    $currentPage,
                    ['path' => route('user.classes')]
                );

                return view('pages.platform.classesStudent', compact('user', 'paginatedItems', 'invitations', 'classes'));

            default:
                abort(403, 'Нет доступа');
        }
    }

    public function showAssignmentsPage()
    {
        $user = $this->getAuthenticatedUser();
        $perPage = 6;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        switch ($user->role) {
            case 'teacher':
                $classes = $this->getUserClasses($user);
                $allAssignments = Assignments::where('teacher_id', $user->id)->get();

                $paginatedItems = new LengthAwarePaginator(
                    $allAssignments->forPage($currentPage, $perPage),
                    $allAssignments->count(),
                    $perPage,
                    $currentPage,
                    ['path' => route('user.assignments')]
                );

                return view('pages.platform.assignmentsTeacher', compact('user', 'classes', 'paginatedItems'));

            case 'admin':
                return view('pages.platform.dashboardAdmin', compact('user'));
            case 'student':
                $classes = $user->classes;
                $assignments = [];
                foreach ($classes as $class) {
                    $classAssignments = $class->assignments;

                    foreach ($classAssignments as $assignment) {
                        $submission = StudentAssignments::where('user_id', $user->id)
                            ->where('assignment_id', $assignment->id)
                            ->first();

                        $isCompleted = false;
                        if ($submission) {
                            $isCompleted = in_array($submission->status, ['submitted', 'graded']);
                        }

                        $assignments[] = [
                            'class' => $class,
                            'assignment' => $assignment,
                            'completed' => $isCompleted,
                            'submission' => $submission,
                        ];
                    }
                }
                $assignments = collect($assignments);
                return view('pages.platform.assignmentsStudent', compact('user', 'classes', 'assignments'));
            default:
                abort(403, 'Нет доступа');
        }
    }

    public function showCalendarPage()
    {
        $user = $this->getAuthenticatedUser();

        switch ($user->role) {
            case 'teacher':
                $classes = $this->getUserClasses($user);

                $assignments = Assignments::where('teacher_id', $user->id)->with('class')->get();

                $assignmentData = $assignments->map(function ($assignment) {
                    return [
                        'id' => $assignment->id,
                        'title' => $assignment->title,
                        'description' => $assignment->description,
                        'due_date' => $assignment->due_date,
                        'class_id' => $assignment->class->id ?? null,
                        'class_name' => $assignment->class->name ?? 'Без класса'
                    ];
                })->toArray();

                return view('pages.platform.calendarTeacher', compact('user', 'classes', 'assignmentData'));

            case 'admin':
                return view('pages.platform.dashboardAdmin', compact('user'));

            case 'student':
                $classes = $this->getUserClasses($user);

                $assignments = [];

                foreach ($classes as $class) {
                    $classAssignments = $class->assignments;

                    foreach ($classAssignments as $assignment) {
                        $submission = StudentAssignments::where('assignment_id', $assignment->id)
                            ->where('user_id', $user->id)
                            ->first();

                        $assignments[] = [
                            'id' => $assignment->id,
                            'title' => $assignment->title,
                            'description' => $assignment->description,
                            'due_date' => $assignment->due_date,
                            'class_name' => optional($assignment->class)->name,
                            'class_id' => optional($assignment->class)->id,
                            'status' => $submission ? $submission->status : 'not_submitted',
                            'completed' => in_array($submission?->status, ['submitted', 'graded']),
                        ];
                    }
                }

                $groupedAssignments = collect($assignments)->groupBy('due_date')->map(function ($group) {
                    return $group->map(function ($item) {
                        return [
                            'id' => $item['id'],
                            'title' => $item['title'],
                            'description' => $item['description'],
                            'due_date' => $item['due_date'],
                            'class_name' => $item['class_name'],
                            'class_id' => $item['class_id'],
                            'status' => $item['status'],
                            'completed' => $item['completed'],
                        ];
                    });
                });

                return view('pages.platform.calendarStudent', compact('user', 'classes', 'groupedAssignments'));
            default:
                abort(403, 'Нет доступа');
        }
    }

    public function showStatisticsPage(Request $request)
    {
        $user = $this->getAuthenticatedUser();

        if ($user->role !== 'teacher') {
            abort(403, 'Нет доступа');
        }

        $classes = $this->getUserClasses($user);
        $assignments = Assignments::where('teacher_id', $user->id)->get();

        $assignmentsByMonth = $assignments->groupBy(fn($item) => \Carbon\Carbon::parse($item->due_date)->format('m'))
            ->map(fn($group) => $group->count())
            ->toArray();

        $studentCounts = $classes->mapWithKeys(fn($class) => [
            $class->name => $class->students()->where('role', 'student')->count()
        ])->toArray();

        $assignmentTypes = $assignments->flatMap(fn($a) => json_decode($a->options, true))
            ->pluck('type')
            ->filter()
            ->countBy()
            ->toArray();

        $totalStudents = array_sum($studentCounts);
        $newAssignmentsCount = $assignments->where('created_at', '>=', now()->subDays(7))->count();

        $selectedClassId = $request->query('class_id', $classes->first()?->id);

        $studentRatings = [];
        if ($selectedClassId) {
            $class = $classes->firstWhere('id', $selectedClassId);

            if ($class) {
                $students = $class->students()->where('role', 'student')->get();

                $studentRatings = $students->mapWithKeys(function ($student) use ($selectedClassId) {
                    $assignmentIds = Assignments::where('class_id', $selectedClassId)->pluck('id');

                    $avgGrade = $student->studentAssignments()
                        ->whereIn('assignment_id', $assignmentIds)
                        ->whereNotNull('grade')
                        ->avg('grade');

                    return [$student->name . ' ' . $student->surname => round($avgGrade ?? 0, 2)];
                })->sortDesc()->toArray();
            }
        }

        return view('pages.platform.statisticsTeacher', compact(
            'user',
            'classes',
            'assignments',
            'assignmentsByMonth',
            'studentCounts',
            'assignmentTypes',
            'totalStudents',
            'newAssignmentsCount',
            'studentRatings',
            'selectedClassId'
        ));
    }

    public function showProfilePage()
    {
        $user = $this->getAuthenticatedUser();

        switch ($user->role) {
            case 'teacher':
                $classes = $this->getUserClasses($user);
                return view('pages.user.profile', compact('user', 'classes'));
            case 'admin':
                $classes = Classes::all();
                return view('pages.user.profile', compact('user', 'classes'));
            case 'student':
                $classes = $this->getUserClasses($user);
                return view('pages.user.profile', compact('user', 'classes'));
            default:
                abort(403, 'Нет доступа');
        }
    }

    public function showEditAvatarChoosePage()
    {
        $user = $this->getAuthenticatedUser();

        switch ($user->role) {
            case 'teacher':
                $classes = $this->getUserClasses($user);
                return view('pages.user.chooseAvatar', compact('user', 'classes'));
            case 'admin':
                $classes = Classes::all();
                return view('pages.user.chooseAvatar', compact('user', 'classes'));
            case 'student':
                $classes = $this->getUserClasses($user);
                return view('pages.user.chooseAvatar', compact('user', 'classes'));
            default:
                abort(403, 'Нет доступа');
        }
    }

    public function createClass()
    {
        $user = $this->getAuthenticatedUser();
        if ($user->role == 'admin' || $user->role == 'teacher') {
            $teachers = User::where('role', 'teacher')->get();
            if ($user->role == 'teacher') {
                $classes = $this->getUserClasses($user);
            } else {
                $classes = Classes::all();
            }
            return view('pages.classes.create', compact('teachers', 'user', 'classes'));
        }
        abort(403, 'Нет доступа');
    }

    public function createAssignments($classId = null)
    {
        $user = $this->getAuthenticatedUser();
        if ($user->role === 'admin') {
            $classes = Classes::all();
        } else {
            $classes = $this->getUserClasses($user);
        }

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

        if ($role == 'teacher') {
            $assignments = $class->assignments()->where('teacher_id', $user->id)->get();

            $students = $class->students;

            $studentProgress = $students->map(function ($student) use ($assignments) {
                $totalAssignments = $assignments->count();
                if ($totalAssignments === 0) {
                    return [
                        'student' => $student,
                        'completed' => 0,
                        'percent' => 0,
                        'average_grade' => 0,
                        'submissions' => [],
                    ];
                }

                $completedSubmissions = StudentAssignments::where('user_id', $student->id)
                    ->whereIn('assignment_id', $assignments->pluck('id'))
                    ->where('status', 'graded')
                    ->get();

                $completedCount = $completedSubmissions->count();
                $percent = round(($completedCount / $totalAssignments) * 100);

                $grades = $completedSubmissions->pluck('grade')->filter(fn($g) => !is_null($g));
                $averageGrade = $grades->isNotEmpty()
                    ? $grades->avg()
                    : null;

                return [
                    'student' => $student,
                    'completed' => $completedCount,
                    'total' => $totalAssignments,
                    'percent' => $percent,
                    'average_grade' => $averageGrade,
                    'submissions' => $completedSubmissions,
                ];
            });

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
                'availableStudents',
                'studentProgress'
            ));
        } else {
            $assignments = [];
            $classes = $this->getUserClasses($user);

            foreach ($class->assignments as $assignment) {
                $submission = StudentAssignments::where('user_id', $user->id)
                    ->where('assignment_id', $assignment->id)
                    ->first();

                $assignments[] = [
                    'assignment' => $assignment,
                    'submission' => $submission,
                ];
            }

            $completedAssignments = count(array_filter($assignments, fn($a) => in_array($a['submission']?->status, ['submitted', 'graded'])));

            return view('pages.classes.classStudent', compact('class', 'assignments', 'completedAssignments', 'classes', 'user'));
        }
    }

    public function showAssignmentPage($id)
    {
        $user = $this->getAuthenticatedUser();
        $role = $user->role;
        $classes = $this->getUserClasses($user);

        if ($role == 'teacher') {
            $assignment = Assignments::with('studentAssignments')->findOrFail($id);
            $assignmentFields = json_decode($assignment->options, true);

            $totalStudents = $assignment->students()->count();
            $submissions = $assignment->studentAssignments;

            $stats = [
                'total_students' => $totalStudents,
                'submitted_count' => $submissions->where('status', 'submitted')->count(),
                'graded_count' => $submissions->where('status', 'graded')->count(),
                'not_submitted_count' => $submissions->where('status', 'not_submitted')->count(),
                'average_grade' => round($submissions->avg('grade'), 1) ?: 0,
                'completed_count' => $submissions->whereIn('status', ['submitted', 'graded'])->count(),
            ];

            return view('pages.assignments.assignment', compact(
                'user',
                'classes',
                'assignment',
                'assignmentFields',
                'stats'
            ));
        } else {
            $assignment = Assignments::findOrFail($id);
            $assignmentFields = json_decode($assignment->options, true);
            return view('pages.assignments.assignmentStudent', compact('user', 'classes', 'assignment', 'assignmentFields'));
        }
    }

    public function showAssignmentsToGrade()
    {
        $user = $this->getAuthenticatedUser();

        switch ($user->role) {
            case 'teacher':
                $classes = $this->getUserClasses($user);
                $assignmentsToGrade = $this->getUserAssignmentsToGrade($user);

                return view('pages.assignments.assignmensToGrade', compact('user', 'classes', 'assignmentsToGrade'));
            case 'admin':
                return view('pages.platform.dashboardAdmin', compact('user'));
            default:
                abort(403, 'Нет доступа');
        }
    }

    public function gradeForm($id)
    {
        $user = $this->getAuthenticatedUser();
        $role = $user->role;
        $classes = $this->getUserClasses($user);
        if ($role === 'teacher') {
            $assignment = Assignments::with('class')->findOrFail($id);
            $studentAssignment = StudentAssignments::where('assignment_id', $id)
                ->where('status', 'submitted')
                ->firstOrFail();

            $answers = json_decode($studentAssignment->student_answer, true) ?? [];
            $assignmentFields = json_decode($assignment->options, true) ?? [];

            $totalCorrect = 0;
            $totalPossible = 0;
            $detailedStats = [];

            foreach ($assignmentFields as $index => $field) {
                $questionTitle = $field['name'] ?? 'Вопрос ' . ($index + 1);
                $questionType = $field['type'] ?? 'unknown';

                if (in_array($questionType, ['file_upload', 'text'])) {
                    $detailedStats[] = [
                        'question' => $questionTitle,
                        'type' => $questionType,
                        'correct_needed' => 0,
                        'correct_given' => 0,
                        'percent' => 'N/A',
                    ];
                    continue;
                }

                $correctOptions = array_filter($field['options'] ?? [], fn($opt) => $opt['isCorrect'] ?? false);
                $correctCountInField = count($correctOptions);

                $studentAnswer = $answers[$index] ?? [];
                $selectedOptions = $studentAnswer['selected_options'] ?? [];
                $selectedCorrect = array_intersect_key(
                    array_flip($selectedOptions),
                    $correctOptions
                );
                $studentCorrectInField = count($selectedCorrect);

                $detailedStats[] = [
                    'question' => $questionTitle,
                    'type' => $questionType,
                    'correct_needed' => $correctCountInField,
                    'correct_given' => $studentCorrectInField,
                    'percent' => $correctCountInField > 0 ? round(($studentCorrectInField / $correctCountInField) * 100) : 100,
                ];

                $totalCorrect += $studentCorrectInField;
                $totalPossible += $correctCountInField;
            }

            $percentCorrect = $totalPossible > 0 ? round(($totalCorrect / $totalPossible) * 100) : 100;

            $autoGrade = $percentCorrect;
            $autoFeedback = match (true) {
                $percentCorrect >= 90 => "Отличная работа! Все вопросы выполнены на высоком уровне.",
                $percentCorrect >= 70 => "Хорошо выполнено. Есть небольшие ошибки.",
                $percentCorrect >= 50 => "Работа выполнена удовлетворительно. Рекомендуется повторить материал.",
                default => "Много ошибок. Рекомендую пересмотреть тему и попробовать снова.",
            };

            return view('pages.assignments.gradeForm', compact(
                'user',
                'assignment',
                'assignmentFields',
                'studentAssignment',
                'answers',
                'percentCorrect',
                'autoGrade',
                'autoFeedback',
                'detailedStats',
                'classes'
            ));
        }

        return redirect()->route('assignment.result');
    }

    public function showAssignmentResult($id)
    {
        $user = auth()->user();
        $classes = $this->getUserClasses($user);

        $studentAssignment = StudentAssignments::with(['assignment.class', 'user'])->findOrFail($id);

        if ($user->role !== 'teacher' && $user->id !== $studentAssignment->user_id) {
            abort(403, 'У вас нет доступа к этому заданию');
        }

        $answers = json_decode($studentAssignment->student_answer, true) ?? [];
        $questions = json_decode($studentAssignment->assignment->options, true) ?? [];

        $results = [];
        $detailedStats = [];

        $totalCorrect = 0;
        $totalPossible = 0;

        foreach ($questions as $index => $question) {
            $answer = $answers[$index] ?? null;

            $questionTitle = $question['name'] ?? 'Вопрос ' . ($index + 1);
            $questionType = $question['type'] ?? 'unknown';

            $isCorrect = false;
            if ($answer && in_array($questionType, ['single_choice', 'multiple_choice'])) {
                $selected = $answer['selected_options'] ?? [];
                $correctIndices = array_keys(array_filter($question['options'] ?? [], fn($opt) => $opt['isCorrect'] ?? false));

                $isCorrect = count(array_intersect($selected, $correctIndices)) === count($correctIndices)
                    && count($selected) === count($correctIndices);
            }

            $results[] = [
                'question_text' => $questionTitle,
                'type' => $questionType,
                'options' => $question['options'] ?? [],
                'student_answer' => $answer,
                'isCorrect' => $isCorrect,
            ];

            if (in_array($questionType, ['file_upload', 'text'])) {
                $detailedStats[] = [
                    'question' => $questionTitle,
                    'type' => $questionType,
                    'correct_needed' => 0,
                    'correct_given' => 0,
                    'percent' => 'N/A',
                ];
                continue;
            }

            $correctOptions = array_filter($question['options'] ?? [], fn($opt) => $opt['isCorrect'] ?? false);
            $correctCountInField = count($correctOptions);

            $selectedOptions = $answer['selected_options'] ?? [];
            $selectedCorrect = array_intersect_key(
                array_flip($selectedOptions),
                $correctOptions
            );
            $studentCorrectInField = count($selectedCorrect);

            $percent = $correctCountInField > 0
                ? round(($studentCorrectInField / $correctCountInField) * 100)
                : 100;

            $detailedStats[] = [
                'question' => $questionTitle,
                'type' => $questionType,
                'correct_needed' => $correctCountInField,
                'correct_given' => $studentCorrectInField,
                'percent' => $percent,
            ];

            $totalCorrect += $studentCorrectInField;
            $totalPossible += $correctCountInField;
        }

        $percentCorrect = $totalPossible > 0 ? round(($totalCorrect / $totalPossible) * 100) : 100;

        return view('pages.assignments.showAssignmentResult', compact(
            'studentAssignment',
            'results',
            'percentCorrect',
            'classes',
            'user',
            'detailedStats'
        ));
    }

    public function adminHome()
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) {
            return redirect()->route('login');
        }
        $role = $user->role;
        if ($role !== 'admin') {
            return redirect()->back();
        }
        $users = User::all();
        $classes = Classes::withCount('students')->get();
        $assignments = Assignments::with('class')->latest()->take(5)->get();

        return view('pages.admin.dashboard', compact('users', 'classes', 'assignments', 'user'));
    }

    public function adminUsers()
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) {
            return redirect()->route('login');
        }
        $role = $user->role;
        if ($role !== 'admin') {
            return redirect()->back();
        }
        $users = User::paginate(10);
        $classes = Classes::all();

        return view('pages.admin.users', compact('users', 'user', 'role', 'classes'));
    }

    public function adminAssignments()
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) {
            return redirect()->route('login');
        }
        $role = $user->role;
        if ($role !== 'admin') {
            return redirect()->back();
        }
        $assignments = Assignments::with('class')->paginate(10);
        $classes = Classes::all();

        return view('pages.admin.assignments', compact('assignments', 'user', 'role', 'classes'));
    }

    public function adminClasses()
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) {
            return redirect()->route('login');
        }
        $role = $user->role;
        if ($role !== 'admin') {
            return redirect()->back();
        }
        $classes = Classes::withCount('students')->paginate(10);
        return view('pages.admin.classes', compact('classes', 'user', 'role'));
    }
}
