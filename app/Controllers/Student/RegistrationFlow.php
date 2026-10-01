<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\CourseModel;

class RegistrationFlow extends BaseController
{
    protected $courseModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
    }

    public function index()
    {
        $registrationMain = $this->getStudentSession('registration_main');
        $studentNumber    = $this->getStudentSession('student_number');
        $fullName         = $this->getStudentSession('full_name');
        $email            = $this->getStudentSession('email');
        $isStudentLogged  = $this->getStudentSession('is_student_logged_in');

        if (!$registrationMain || !$studentNumber || !$fullName || !$email || !$isStudentLogged) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir atau tidak valid.');
        }

        $activeTab = $this->request->getGet('tab') ?? 'requirements';

        $coursesData    = $this->courseModel->getActiveCoursesGroupedByFaculty();
        $groupedCourses = [];

        foreach ($coursesData as $course) {
            $facName  = $course->faculty_name;
            $progName = $course->program_name;

            if (!isset($groupedCourses[$facName])) {
                $groupedCourses[$facName] = [];
            }
            if (!isset($groupedCourses[$facName][$progName])) {
                $groupedCourses[$facName][$progName] = [];
            }

            $groupedCourses[$facName][$progName][] = $course;
        }

        $this->logActivity(
            'VIEW_REGISTRATION_FLOW',
            'Mahasiswa melihat halaman alur dan informasi pendaftaran',
            [
                'registration_main' => $registrationMain,
                'student_number'    => $studentNumber,
                'active_tab'        => $activeTab,
            ],
            'student'
        );

        $data = [
            'title'          => 'Alur & Informasi Pendaftaran Asisten',
            'activeTab'      => $activeTab,
            'groupedCourses' => $groupedCourses,
        ];

        return view('student/registration_flow/index', $data);
    }
}
