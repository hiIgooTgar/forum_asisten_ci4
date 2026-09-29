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
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $activeTab = $this->request->getGet('tab') ?? 'requirements';

        $coursesData = $this->courseModel->getActiveCoursesGroupedByFaculty();
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


        $appProfileModel = new \App\Models\CompanyApplicationModel();
        $appProfile = $appProfileModel->first();


        $data = [
            'title'          => 'Alur & Informasi Pendaftaran Asisten',
            'activeTab'      => $activeTab,
            'groupedCourses' => $groupedCourses,
            'appProfile'     => $appProfile
        ];

        return view('student/registration_flow/index', $data);
    }
}
