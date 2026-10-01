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
        $registrationMain = $this->getStudentSession('registration_main');
        $studentNumber    = $this->getStudentSession('student_number');
        $fullName         = $this->getStudentSession('full_name');
        $email            = $this->getStudentSession('email');
        $isStudentLogged  = $this->getStudentSession('is_student_logged_in');

        if (!$registrationMain || !$studentNumber || !$fullName || !$email || !$isStudentLogged) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir atau tidak valid.');
        }

        $student      = $this->verificationModel->getStudentVerificationData($registrationMain);
        $takenCourses = $this->verificationModel->getTakenCoursesByRegistrationMain($registrationMain);

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
            if (empty($student->$field)) {
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
        $registrationMain = $this->getStudentSession('registration_main');
        $studentNumber    = $this->getStudentSession('student_number');
        $fullName         = $this->getStudentSession('full_name');
        $email            = $this->getStudentSession('email');
        $isStudentLogged  = $this->getStudentSession('is_student_logged_in');

        if (!$registrationMain || !$studentNumber || !$fullName || !$email || !$isStudentLogged) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir atau tidak valid.');
        }

        $student = $this->verificationModel->getStudentVerificationData($registrationMain);

        if (!$student) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        if (($student->verification_status ?? 'unsubmitted') === 'completed') {
            return redirect()->back()->with('error', 'Pendaftaran Anda sudah dikirim sebelumnya dan tidak dapat diubah lagi.');
        }

        $takenCourses = $this->verificationModel->getTakenCoursesByRegistrationMain($registrationMain);
        if (empty($takenCourses)) {
            return redirect()->back()->with('error', 'Gagal memproses. Anda belum memasukkan mata kuliah yang diambil.');
        }

        $updated = $this->verificationModel->completeVerification($registrationMain);
        if ($updated) {
            if (method_exists($this, 'logActivity')) {
                $this->logActivity(
                    'SUBMIT_VERIFICATION',
                    'Mahasiswa berhasil mengirim verifikasi pendaftaran',
                    [
                        'registration_main' => $registrationMain,
                        'student_number'    => $studentNumber,
                    ],
                    'student'
                );
            }

            return redirect()->to('/student/verification')->with('success', 'Pendaftaran berhasil dikirim. Seluruh data Anda telah dikunci.');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui status verifikasi. Silakan coba lagi.');
    }
}
