<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\student\StudentDashboardModel;
use App\Models\TakenCourseModel;
use App\Models\UserDocumentModel;

class Dashboard extends BaseController
{
    protected $dashboardModel;
    protected $takenCourseModel;
    protected $userDocumentModel;

    public function __construct()
    {
        $this->dashboardModel = new StudentDashboardModel();
        $this->takenCourseModel = new TakenCourseModel();
        $this->userDocumentModel = new UserDocumentModel();
    }

    public function index()
    {
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $student   = $this->dashboardModel->getStudentProfile((int)$userId);
        $documents = $this->dashboardModel->getDocumentStatus((int)$userId);

        if (!$student) {
            return redirect()->to('/auth/login')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $requiredFields = [
            'student_number',
            'full_name',
            'email',
            'phone_number',
            'faculty_id',
            'study_program_id',
            'place_of_birth',
            'date_of_birth',
            'gender',
            'province',
            'regency',
            'subdistrict',
            'village',
            'address',
            'gpa',
        ];

        $profileIncomplete = false;
        foreach ($requiredFields as $field) {
            if (empty($student->$field)) {
                $profileIncomplete = true;
                break;
            }
        }

        if (!$profileIncomplete) {
            $profilePhoto = trim($student->profile ?? '');
            if (empty($profilePhoto) || $profilePhoto === 'profile-default.png') {
                $profileIncomplete = true;
            }
        }

        $takenCoursesCount = $this->takenCourseModel->countUserTakenCourses((int)$userId);
        $hasTakenCourses   = ($takenCoursesCount > 0);

        $allDocumentsUploaded = false;
        if ($documents) {
            $docFields = [
                'student_card_file',
                'application_letter_file',
                'cv_file',
                'latest_transcript_file',
                'statement_letter_file',
                'registration_form_file'
            ];

            $uploadedCount = 0;
            foreach ($docFields as $field) {
                if (!empty($documents->$field)) {
                    $uploadedCount++;
                }
            }

            $allDocumentsUploaded = ($uploadedCount === count($docFields));
        }

        $isAlreadyVerified = isset($student->verification_status) && $student->verification_status === 'completed';
        $canVerify = (!$profileIncomplete && $hasTakenCourses && $allDocumentsUploaded && !$isAlreadyVerified);

        $completionPercentage = $this->calculateProfileCompletion($student, $documents);

        $this->logActivity(
            'VIEW_DASHBOARD',
            'Mahasiswa melihat halaman dashboard utama',
            [
                'student_number'        => $studentNumber,
                'completion_percentage' => $completionPercentage,
                'can_verify'            => $canVerify,
            ],
            'student'
        );

        $appProfileModel = new \App\Models\CompanyApplicationModel();
        $appProfile = $appProfileModel->first();

        $data = [
            'title'                 => 'Dashboard Student',
            'student'               => $student,
            'documents'             => $documents,
            'active_event'          => $this->dashboardModel->getActiveEvent(),
            'completion_percentage' => $completionPercentage,
            'canVerify'             => $canVerify,
            'appProfile'            => $appProfile

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
            'province',
            'regency',
            'subdistrict',
            'village',
            'address',
            'gpa',
            'profile'
        ];

        if (!empty($student)) {
            $filledUserFields = 0;
            foreach ($userFields as $field) {
                if (isset($student->$field) && $student->$field !== '' && $student->$field !== null) {

                    if ($field === 'profile') {
                        $defaultProfiles = ['profile-default.png', 'default.png'];
                        $profileValue    = trim((string)$student->$field);
                        if (in_array($profileValue, $defaultProfiles, true) || empty($profileValue)) {
                            continue;
                        }
                    }

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
