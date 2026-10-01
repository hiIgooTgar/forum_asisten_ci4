<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\TakenCourseModel;
use App\Models\CourseModel;
use App\Models\UserModel;
use App\Models\userDocumentModel;

class TakenCourseData extends BaseController
{
    protected $takenCourseModel;
    protected $courseModel;
    protected $userModel;
    protected $userDocumentModel;

    public function __construct()
    {
        $this->takenCourseModel  = new TakenCourseModel();
        $this->courseModel       = new CourseModel();
        $this->userModel         = new UserModel();
        $this->userDocumentModel = new userDocumentModel();
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

        $student = $this->userModel->getStudentProfile($registrationMain);

        if (!$student) {
            return redirect()->to('/auth/login')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $requiredFields = [
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
            $profilePhoto = basename(trim((string) ($student->profile ?? '')));
            if (empty($profilePhoto) || in_array($profilePhoto, ['profile-default.png', 'default.png'], true)) {
                $profileIncomplete = true;
            }
        }

        $takenCourses     = $this->takenCourseModel->getTakenCoursesByUser($registrationMain);
        $hasTakenCourses  = count($takenCourses) > 0;

        $availableCourses = [];
        if (!$profileIncomplete && !empty($student->faculty_params)) {
            $availableCourses = $this->courseModel->getCoursesByFacultyParam($student->faculty_params);
        }

        $document             = $this->userDocumentModel->getDocumentByUserParams($registrationMain);
        $allDocumentsUploaded = false;

        if ($document) {
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
                if (!empty($document->$field)) {
                    $uploadedCount++;
                }
            }

            $allDocumentsUploaded = ($uploadedCount === count($docFields));
        }

        $isAlreadyVerified = isset($student->verification_status) && $student->verification_status === 'completed';
        $canVerify         = (!$profileIncomplete && $hasTakenCourses && $allDocumentsUploaded && !$isAlreadyVerified);

        $data = [
            'title'             => 'Pendaftaran Mata Kuliah',
            'student'           => $student,
            'takenCourses'      => $takenCourses,
            'availableCourses'  => $availableCourses,
            'profileIncomplete' => $profileIncomplete,
            'canVerify'         => $canVerify,
            'activeTab'         => 'taken_courses',
        ];

        return view('student/taken_courses/index', $data);
    }

    public function store()
    {
        $registrationMain = $this->getStudentSession('registration_main');
        $studentNumber    = $this->getStudentSession('student_number');

        if (!$registrationMain || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $student = $this->userModel->getStudentProfile($registrationMain);

        if (isset($student->verification_status) && $student->verification_status === 'completed') {
            return redirect()->back()->with('error', 'Akses ditolak: Pendaftaran Anda telah terverifikasi dan data telah terkunci.');
        }

        $currentCount = (int) $this->takenCourseModel->countUserTakenCourses($registrationMain);
        if ($currentCount >= 3) {
            return redirect()->back()->withInput()->with('errors', [
                'course_params' => 'Batas maksimal pendaftaran adalah 3 mata kuliah. Anda telah mencapai batas maksimum.'
            ]);
        }

        $courseParams = $this->request->getPost('course_params');
        if ($courseParams) {
            $isDuplicate = $this->takenCourseModel
                ->where('user_params', $registrationMain)
                ->where('course_params', $courseParams)
                ->first();

            if ($isDuplicate) {
                return redirect()->back()->withInput()->with('errors', [
                    'course_params' => 'Mata kuliah ini sudah Anda daftarkan sebelumnya.'
                ]);
            }
        }

        $courseData = $this->courseModel->getCourseByParam($courseParams);

        if ($courseData) {
            $quotaNeeded = (int)($courseData->quota_needed ?? 0);
            $totalTaken  = (int)($courseData->total_taken ?? 0);

            if ($quotaNeeded > 0 && $totalTaken >= $quotaNeeded) {
                return redirect()->back()->withInput()->with('errors', [
                    'course_params' => 'Kuota untuk mata kuliah ini sudah penuh.'
                ]);
            }
        }

        $validationRules = [
            'course_params' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Mata kuliah wajib dipilih.',
                ],
            ],
            'grade' => [
                'rules'  => 'required|max_length[3]|in_list[A,A-,B+,B]',
                'errors' => [
                    'required'   => 'Target Nilai / Grade wajib dipilih.',
                    'in_list'    => 'Pilihan Grade tidak valid.',
                    'max_length' => 'Panjang grade maksimal 3 karakter.',
                ],
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pendaftaran data mata kuliah. Silakan periksa kembali form Anda.')->with('errors', $this->validator->getErrors());
        }

        $courseCode      = $courseData->course_code ?? $courseParams;
        $randomNumber    = str_pad(random_int(0, 99999999), 10, '0', STR_PAD_LEFT);
        $takenCourseMain = "{$randomNumber}_{$studentNumber}_{$courseCode}";

        $saveData = [
            'taken_course_main' => $takenCourseMain,
            'user_params'       => $registrationMain,
            'course_params'     => $courseParams,
            'grade'             => $this->request->getPost('grade'),
        ];

        $this->takenCourseModel->insert($saveData);
        $insertId = $this->takenCourseModel->getInsertID();

        $this->logActivity(
            'CREATE_TAKEN_COURSE_SUCCESS',
            'Mahasiswa berhasil mendaftarkan mata kuliah baru',
            [
                'student_number'    => $studentNumber,
                'taken_course_main' => $takenCourseMain,
                'taken_course_params'   => $insertId,
                'data'              => $saveData,
            ],
            'student'
        );

        return redirect()->to('/student/taken-courses')->with('success', 'Mata kuliah berhasil didaftarkan.');
    }

    public function update($takenCourseMain)
    {
        $registrationMain = $this->getStudentSession('registration_main');
        $studentNumber    = $this->getStudentSession('student_number');

        if (!$registrationMain || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $student = $this->userModel->getStudentProfile($registrationMain);

        if (isset($student->verification_status) && $student->verification_status === 'completed') {
            return redirect()->back()->with('error', 'Akses ditolak: Pendaftaran Anda telah terverifikasi dan data telah terkunci.');
        }

        $taken = $this->takenCourseModel
            ->where('taken_course_main', $takenCourseMain)
            ->where('user_params', $registrationMain)
            ->first();

        if (!$taken) {
            return redirect()->to('/student/taken-courses')->with('error', 'Data mata kuliah tidak ditemukan.');
        }

        $newCourseParams = $this->request->getPost('course_params');
        $newGrade        = $this->request->getPost('grade');

        $oldCourseParams = is_object($taken) ? $taken->course_params : $taken['course_params'];
        $oldGrade        = is_object($taken) ? $taken->grade : $taken['grade'];

        if ((string)$newCourseParams === (string)$oldCourseParams && (string)$newGrade === (string)$oldGrade) {
            return redirect()
                ->to('/student/taken-courses')
                ->with('info', 'Tidak ada perubahan data mata kuliah.');
        }

        if ((string)$newCourseParams !== (string)$oldCourseParams) {
            $isDuplicate = $this->takenCourseModel
                ->where('user_params', $registrationMain)
                ->where('course_params', $newCourseParams)
                ->where('taken_course_main !=', $takenCourseMain)
                ->first();

            if ($isDuplicate) {
                return redirect()->back()->withInput()->with('errors', [
                    'course_params' => 'Mata kuliah ini sudah Anda daftarkan sebelumnya.'
                ]);
            }

            $newCourseData = $this->courseModel->getCourseByParam($newCourseParams);

            if ($newCourseData) {
                $quotaNeeded = (int)($newCourseData->quota_needed ?? 0);
                $totalTaken  = (int)($newCourseData->total_taken ?? 0);

                if ($quotaNeeded > 0 && $totalTaken >= $quotaNeeded) {
                    return redirect()->back()->withInput()->with('errors', [
                        'course_params' => 'Kuota untuk mata kuliah pilihan baru ini sudah penuh.'
                    ]);
                }
            }
        }

        $validationRules = [
            'course_params' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Mata kuliah wajib dipilih.',
                ],
            ],
            'grade' => [
                'rules'  => 'required|max_length[3]|in_list[A,A-,B+,B]',
                'errors' => [
                    'required'   => 'Target Nilai / Grade wajib dipilih.',
                    'in_list'    => 'Pilihan Grade tidak valid.',
                    'max_length' => 'Panjang grade maksimal 3 karakter.',
                ],
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pendaftaran data mata kuliah. Silakan periksa kembali form Anda.')->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'course_params' => (string)$newCourseParams,
            'grade'         => (string)$newGrade,
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        $targetId = is_object($taken) ? $taken->id : $taken['id'];
        $this->takenCourseModel->update($targetId, $updateData);

        $this->logActivity(
            'UPDATE_TAKEN_COURSE_SUCCESS',
            'Mahasiswa berhasil memperbarui data pendaftaran mata kuliah',
            [
                'student_number'    => $studentNumber,
                'taken_course_main' => $takenCourseMain,
                'old_data'          => [
                    'course_params' => $oldCourseParams,
                    'grade'         => $oldGrade,
                ],
                'new_data'          => $updateData,
            ],
            'student'
        );

        return redirect()->to('/student/taken-courses')->with('success', 'Data mata kuliah berhasil diperbarui.');
    }

    public function delete($takenCourseMain)
    {
        $registrationMain = $this->getStudentSession('registration_main');
        $studentNumber    = $this->getStudentSession('student_number');

        if (!$registrationMain || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $student = $this->userModel->getStudentProfile($registrationMain);

        if (isset($student->verification_status) && $student->verification_status === 'completed') {
            return redirect()->back()->with('error', 'Akses ditolak: Pendaftaran Anda telah terverifikasi dan data telah terkunci.');
        }

        $taken = $this->takenCourseModel
            ->where('taken_course_main', $takenCourseMain)
            ->where('user_params', $registrationMain)
            ->first();

        if (!$taken) {
            return redirect()->to('/student/taken-courses')->with('error', 'Data tidak ditemukan atau akses ditolak.');
        }

        $targetId = is_object($taken) ? $taken->id : $taken['id'];
        $this->takenCourseModel->delete($targetId);

        $this->logActivity(
            'DELETE_TAKEN_COURSE_SUCCESS',
            'Mahasiswa berhasil menghapus mata kuliah dari daftar pendaftaran',
            [
                'student_number'    => $studentNumber,
                'taken_course_main' => $takenCourseMain,
                'deleted_data'      => [
                    'course_params' => is_object($taken) ? $taken->course_params : $taken['course_params'],
                    'grade'         => is_object($taken) ? $taken->grade : $taken['grade'],
                ],
            ],
            'student'
        );

        return redirect()->to('/student/taken-courses')->with('success', 'Mata kuliah berhasil dihapus dari daftar.');
    }
}
