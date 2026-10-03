<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\Student\AnnouncementModel;
use App\Models\SystemEventSettingModel;

class Announcement extends BaseController
{
    protected $announcementModel;
    protected $eventModel;

    public function __construct()
    {
        $this->announcementModel = new AnnouncementModel();
        $this->eventModel        = new SystemEventSettingModel();
    }

    public function index()
    {
        $registrationMain = session()->get('registration_main');
        $studentNumber    = session()->get('student_number');
        $fullName         = session()->get('full_name');
        $email            = session()->get('email');
        $isStudentLogged  = session()->get('is_student_logged_in');

        if (!$registrationMain || !$studentNumber || !$fullName || !$email || !$isStudentLogged) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir atau tidak valid.');
        }

        $student      = $this->announcementModel->getStudentAnnouncement($registrationMain);
        $takenCourses = $this->announcementModel->getTakenCoursesWithResult($registrationMain);
        $event        = $this->eventModel->getEventByKey('announcement_recruitment_result');

        if (!$student) {
            return redirect()->to('/auth/login')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $requiredUserFields = [
            'student_number',
            'full_name',
            'email',
            'phone_number',
            'faculty_params',
            'study_program_params',
            'place_of_birth',
            'date_of_birth',
            'gender',
            'province',
            'regency',
            'subdistrict',
            'village',
            'address',
            'gpa'
        ];

        $profileIncomplete = false;
        foreach ($requiredUserFields as $field) {
            if (empty(trim((string) ($student->$field ?? '')))) {
                $profileIncomplete = true;
                break;
            }
        }

        if (!$profileIncomplete) {
            $profilePhoto = basename(trim((string) ($student->profile ?? '')));
            if (empty($profilePhoto) || in_array($profilePhoto, ['profile-default.png', 'default.png'], true)) {
                $profileIncomplete = true;
            }
        }

        $hasNoTakenCourses = empty($takenCourses);
        $docFields = [
            'student_card_file',
            'application_letter_file',
            'cv_file',
            'latest_transcript_file',
            'statement_letter_file',
            'registration_form_file'
        ];

        $documentsIncomplete = false;
        foreach ($docFields as $doc) {
            $fileValue = trim((string) ($student->$doc ?? ''));
            if (empty($fileValue)) {
                $documentsIncomplete = true;
                break;
            }
        }

        $isVerifiedCompleted = ($student->verification_status ?? 'unsubmitted') === 'completed';
        $isRegistrationIncomplete = ($profileIncomplete || $hasNoTakenCourses || $documentsIncomplete || !$isVerifiedCompleted);

        $isActive = false;
        if (!empty($event)) {
            $isActive = isset($event['is_active']) && ($event['is_active'] == 1 || $event['is_active'] === true);
        }

        $isPublished = false;
        if ($isActive) {
            $isPublished = $this->eventModel->isEventCurrentlyOpen('announcement_recruitment_result');
        }

        $data = [
            'title'                    => 'Pengumuman Kelulusan Asisten',
            'student'                  => $student,
            'takenCourses'             => $takenCourses,
            'event'                    => (object) ($event ?? []),
            'eventSettings'            => $event ?? [],
            'isActive'                 => $isActive,
            'isPublished'              => $isPublished,
            'targetTime'               => $event['start_at'] ?? null,
            'profileIncomplete'        => $profileIncomplete,
            'hasNoTakenCourses'        => $hasNoTakenCourses,
            'documentsIncomplete'      => $documentsIncomplete,
            'isVerifiedCompleted'      => $isVerifiedCompleted,
            'isRegistrationIncomplete' => $isRegistrationIncomplete
        ];

        return view('student/announcement/index', $data);
    }
}
