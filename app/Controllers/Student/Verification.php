<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\Student\UserVerificationModel;

class Verification extends BaseController
{
    protected $verificationModel;

    public function __construct()
    {
        $this->verificationModel = new UserVerificationModel();
    }

    public function index()
    {
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $student      = $this->verificationModel->getStudentVerificationData($userId);
        $takenCourses = $this->verificationModel->getTakenCoursesByUserId($userId);

        $requiredUserFields = [
            'student_number',
            'full_name',
            'email',
            'phone_number',
            'faculty_id',
            'study_program_id',
            'class_id',
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
        if ($student) {
            foreach ($requiredUserFields as $field) {
                if (empty($student->$field)) {
                    $profileIncomplete = true;
                    break;
                }
            }

            $profilePhoto = trim($student->profile ?? '');
            if (empty($profilePhoto) || $profilePhoto === 'profile-default.png') {
                $profileIncomplete = true;
            }
        } else {
            $profileIncomplete = true;
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
        $uploadedDocsCount   = 0;

        foreach ($docFields as $doc) {
            if (!empty($student->$doc)) {
                $uploadedDocsCount++;
            } else {
                $documentsIncomplete = true;
            }
        }

        $isCompleted           = ($student->verification_status ?? 'unsubmitted') === 'completed';
        $canSubmitVerification = (!$profileIncomplete && !$hasNoTakenCourses && !$documentsIncomplete && !$isCompleted);

        $data = [
            'title'                 => 'Verifikasi Pendaftaran Asisten',
            'student'               => $student,
            'takenCourses'          => $takenCourses,
            'profileIncomplete'     => $profileIncomplete,
            'hasNoTakenCourses'     => $hasNoTakenCourses,
            'documentsIncomplete'   => $documentsIncomplete,
            'uploadedDocsCount'     => $uploadedDocsCount,
            'totalDocsCount'        => count($docFields),
            'canSubmitVerification' => $canSubmitVerification,
            'isCompleted'           => $isCompleted,
        ];

        return view('student/verification/index', $data);
    }

    public function submit()
    {
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $student = $this->verificationModel->getStudentVerificationData($userId);

        if ($student->verification_status === 'completed') {
            return redirect()->back()->with('error', 'Pendaftaran Anda sudah dikirim sebelumnya dan tidak dapat diubah lagi.');
        }

        $takenCourses = $this->verificationModel->getTakenCoursesByUserId($userId);

        if (!$student || empty($takenCourses)) {
            return redirect()->back()->with('error', 'Gagal memproses. Persyaratan pendaftaran belum lengkap.');
        }

        $this->verificationModel->completeVerification($userId);

        return redirect()->to('/student/verification')->with('success', 'Pendaftaran berhasil dikirim. Seluruh data Anda telah dikunci.');
    }
}
