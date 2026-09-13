<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\student\StudentDashboardModel;

class Dashboard extends BaseController
{
    protected $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new StudentDashboardModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');

        $student   = $this->dashboardModel->getStudentProfile((int)$userId);
        $documents = $this->dashboardModel->getDocumentStatus((int)$userId);

        $data = [
            'title'                 => 'Dashboard Student',
            'student'               => $student,
            'documents'             => $documents,
            'active_event'          => $this->dashboardModel->getActiveEvent(),
            'completion_percentage' => $this->calculateProfileCompletion($student, $documents),
        ];

        return view('student/dashboard', $data);
    }

    private function calculateProfileCompletion($student, $documents): int
    {
        $userScore = 0;
        $docScore  = 0;

        $userFields = [
            'student_number',
            'full_name',
            'email',
            'phone_number',
            'faculty_id',
            'study_program_id',
            'place_of_birth',
            'date_of_birth',
            'gender',
            'address',
            'gpa'
        ];

        if (!empty($student)) {
            $filledUserFields = 0;
            foreach ($userFields as $field) {
                if (isset($student->$field) && $student->$field !== '' && $student->$field !== null) {
                    $filledUserFields++;
                }
            }
            $userScore = ($filledUserFields / count($userFields)) * 70;
        }

        $docFields = [
            'student_card_file',
            'application_letter_file',
            'cv_file',
            'latest_transcript_file',
            'statement_letter_file',
            'registration_form_file'
        ];

        if (!empty($documents)) {
            $filledDocFields = 0;
            foreach ($docFields as $field) {
                if (isset($documents->$field) && !empty($documents->$field)) {
                    $filledDocFields++;
                }
            }
            $docScore = ($filledDocFields / count($docFields)) * 30;
        }

        return (int) round($userScore + $docScore);
    }
}
