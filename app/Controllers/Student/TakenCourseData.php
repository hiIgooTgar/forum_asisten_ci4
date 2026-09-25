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
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $student = $this->userModel->getStudentProfile($userId);

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

        $takenCourses    = $this->takenCourseModel->getTakenCoursesByUser($userId);
        $hasTakenCourses = count($takenCourses) > 0;

        $availableCourses = [];
        if (!$profileIncomplete && !empty($student->faculty_id)) {
            $availableCourses = $this->courseModel->getCoursesByFaculty($student->faculty_id);
        }

        $document             = $this->userDocumentModel->getDocumentByUserId($userId);
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

        $this->logActivity(
            'VIEW_TAKEN_COURSES',
            'Mahasiswa melihat daftar pendaftaran mata kuliah',
            [
                'student_number'     => $studentNumber,
                'profile_incomplete' => $profileIncomplete,
                'taken_count'        => count($takenCourses),
                'can_verify'         => $canVerify,
            ],
            'student'
        );

        $appProfileModel = new \App\Models\CompanyApplicationModel();
        $appProfile = $appProfileModel->first();


        $data = [
            'title'             => 'Pendaftaran Mata Kuliah',
            'student'           => $student,
            'takenCourses'      => $takenCourses,
            'availableCourses'  => $availableCourses,
            'profileIncomplete' => $profileIncomplete,
            'canVerify'         => $canVerify,
            'activeTab'         => 'taken_courses',
            'appProfile'        => $appProfile
        ];

        return view('student/taken_courses/index', $data);
    }

    public function store()
    {
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');
        $student = $this->userModel->getStudentProfile($userId);

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        if (isset($student->verification_status) && $student->verification_status === 'completed') {
            return redirect()->back()->with('error', 'Akses ditolak: Pendaftaran Anda telah terverifikasi dan data telah terkunci.');
        }

        $currentCount = $this->takenCourseModel->countUserTakenCourses($userId);
        if ($currentCount >= 3) {
            $this->logActivity(
                'FAILED_CREATE_TAKEN_COURSE_LIMIT',
                'Gagal mendaftarkan mata kuliah karena sudah mencapai batas maksimum (3 mata kuliah)',
                [
                    'student_number' => $studentNumber,
                    'current_count'  => $currentCount,
                ],
                'student'
            );

            return redirect()->back()->withInput()->with('errors', [
                'course_id' => 'Batas maksimal pendaftaran adalah 3 mata kuliah. Anda telah mencapai batas maksimum.'
            ]);
        }

        $courseId = $this->request->getPost('course_id');
        if ($courseId) {
            $isDuplicate = $this->takenCourseModel
                ->where('user_id', $userId)
                ->where('course_id', $courseId)
                ->first();

            if ($isDuplicate) {
                $this->logActivity(
                    'FAILED_CREATE_TAKEN_COURSE_DUPLICATE',
                    'Gagal mendaftarkan mata kuliah karena sudah pernah didaftarkan sebelumnya',
                    [
                        'student_number' => $studentNumber,
                        'course_id'      => $courseId,
                    ],
                    'student'
                );

                return redirect()->back()->withInput()->with('errors', [
                    'course_id' => 'Mata kuliah ini sudah Anda daftarkan sebelumnya.'
                ]);
            }
        }

        $courseData = $this->courseModel
            ->select('courses.*, (SELECT COUNT(*) FROM taken_courses WHERE taken_courses.course_id = courses.id) as total_taken')
            ->find($courseId);

        if ($courseData) {
            $quotaNeeded = (int)($courseData->quota_needed ?? 0);
            $totalTaken  = (int)($courseData->total_taken ?? 0);

            if ($quotaNeeded > 0 && $totalTaken >= $quotaNeeded) {
                $this->logActivity(
                    'FAILED_CREATE_TAKEN_COURSE_QUOTA_FULL',
                    'Gagal mendaftarkan mata kuliah karena kuota sudah penuh',
                    [
                        'student_number' => $studentNumber,
                        'course_id'      => $courseId,
                        'quota_needed'   => $quotaNeeded,
                        'total_taken'    => $totalTaken,
                    ],
                    'student'
                );

                return redirect()->back()->withInput()->with('errors', [
                    'course_id' => 'Kuota untuk mata kuliah ini sudah penuh.'
                ]);
            }
        }

        $validationRules = [
            'course_id' => [
                'rules'  => 'required|is_natural_no_zero',
                'errors' => [
                    'required'           => 'Mata kuliah wajib dipilih.',
                    'is_natural_no_zero' => 'Mata kuliah yang dipilih tidak valid.',
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
            $this->logActivity(
                'FAILED_CREATE_TAKEN_COURSE_VALIDATION',
                'Gagal mendaftarkan mata kuliah baru karena kesalahan input form',
                [
                    'student_number' => $studentNumber,
                    'errors'         => $this->validator->getErrors(),
                ],
                'student'
            );

            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pendaftaran data mata kuliah. Silakan periksa kembali form Anda.')->with('errors', $this->validator->getErrors());
        }

        $courseData = $this->courseModel->find($courseId);
        $courseCode = $courseData->code ?? $courseData->course_code ?? $courseId;
        $randomNumber    = str_pad(random_int(0, 99999999), 10, '0', STR_PAD_LEFT);
        $takenCourseCode = "{$randomNumber}_{$studentNumber}_{$courseCode}";

        $saveData = [
            'taken_course_code' => $takenCourseCode,
            'user_id'           => $userId,
            'course_id'         => $courseId,
            'grade'             => $this->request->getPost('grade'),
        ];

        $this->takenCourseModel->insert($saveData);
        $insertId = $this->takenCourseModel->getInsertID();

        $this->logActivity(
            'CREATE_TAKEN_COURSE_SUCCESS',
            'Mahasiswa berhasil mendaftarkan mata kuliah baru',
            [
                'student_number'    => $studentNumber,
                'taken_course_id'   => $insertId,
                'taken_course_code' => $takenCourseCode,
                'data'              => $saveData,
            ],
            'student'
        );

        return redirect()->to('/student/taken-courses')->with('success', 'Mata kuliah berhasil didaftarkan.');
    }

    public function update($takenCourseCode)
    {
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');
        $student = $this->userModel->getStudentProfile($userId);

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        if (isset($student->verification_status) && $student->verification_status === 'completed') {
            return redirect()->back()->with('error', 'Akses ditolak: Pendaftaran Anda telah terverifikasi dan data telah terkunci.');
        }

        $taken = $this->takenCourseModel
            ->where('taken_course_code', $takenCourseCode)
            ->where('user_id', $userId)
            ->first();

        if (!$taken) {
            $this->logActivity(
                'FAILED_UPDATE_TAKEN_COURSE_NOT_FOUND',
                'Gagal memperbarui pendaftaran mata kuliah karena kode tidak ditemukan atau akses ditolak',
                [
                    'student_number'    => $studentNumber,
                    'taken_course_code' => $takenCourseCode,
                ],
                'student'
            );

            return redirect()->to('/student/taken-courses')->with('error', 'Data mata kuliah tidak ditemukan.');
        }

        $newCourseId = $this->request->getPost('course_id');
        $newGrade    = $this->request->getPost('grade');

        $oldCourseId = is_object($taken) ? $taken->course_id : $taken['course_id'];
        $oldGrade    = is_object($taken) ? $taken->grade : $taken['grade'];

        if ((int)$newCourseId === (int)$oldCourseId && (string)$newGrade === (string)$oldGrade) {
            return redirect()
                ->to('/student/taken-courses')
                ->with('info', 'Tidak ada perubahan data mata kuliah.');
        }

        if ((int)$newCourseId !== (int)$oldCourseId) {
            $isDuplicate = $this->takenCourseModel
                ->where('user_id', $userId)
                ->where('course_id', $newCourseId)
                ->where('taken_course_code !=', $takenCourseCode)
                ->first();

            if ($isDuplicate) {
                $this->logActivity(
                    'FAILED_UPDATE_TAKEN_COURSE_DUPLICATE',
                    'Gagal memperbarui mata kuliah karena mata kuliah pilihan sudah didaftarkan sebelumnya',
                    [
                        'student_number'    => $studentNumber,
                        'taken_course_code' => $takenCourseCode,
                        'new_course_id'     => $newCourseId,
                    ],
                    'student'
                );

                return redirect()->back()->withInput()->with('errors', [
                    'course_id' => 'Mata kuliah ini sudah Anda daftarkan sebelumnya.'
                ]);
            }

            $newCourseData = $this->courseModel
                ->select('courses.*, (SELECT COUNT(*) FROM taken_courses WHERE taken_courses.course_id = courses.id) as total_taken')
                ->find($newCourseId);

            if ($newCourseData) {
                $quotaNeeded = (int)($newCourseData->quota_needed ?? 0);
                $totalTaken  = (int)($newCourseData->total_taken ?? 0);

                if ($quotaNeeded > 0 && $totalTaken >= $quotaNeeded) {
                    $this->logActivity(
                        'FAILED_UPDATE_TAKEN_COURSE_QUOTA_FULL',
                        'Gagal memperbarui mata kuliah karena kuota mata kuliah tujuan penuh',
                        [
                            'student_number'    => $studentNumber,
                            'taken_course_code' => $takenCourseCode,
                            'new_course_id'     => $newCourseId,
                            'quota_needed'      => $quotaNeeded,
                            'total_taken'       => $totalTaken,
                        ],
                        'student'
                    );

                    return redirect()->back()->withInput()->with('errors', [
                        'course_id' => 'Kuota untuk mata kuliah pilihan baru ini sudah penuh.'
                    ]);
                }
            }
        }

        $validationRules = [
            'course_id' => [
                'rules'  => 'required|is_natural_no_zero',
                'errors' => [
                    'required'           => 'Mata kuliah wajib dipilih.',
                    'is_natural_no_zero' => 'Mata kuliah yang dipilih tidak valid.',
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
            $this->logActivity(
                'FAILED_UPDATE_TAKEN_COURSE_VALIDATION',
                'Gagal memperbarui data mata kuliah karena kesalahan input form',
                [
                    'student_number'    => $studentNumber,
                    'taken_course_code' => $takenCourseCode,
                    'errors'            => $this->validator->getErrors(),
                ],
                'student'
            );

            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pendaftaran data mata kuliah. Silakan periksa kembali form Anda.')->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'course_id'  => (int)$newCourseId,
            'grade'      => (string)$newGrade,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $targetId = is_object($taken) ? $taken->id : $taken['id'];
        $this->takenCourseModel->update($targetId, $updateData);

        $this->logActivity(
            'UPDATE_TAKEN_COURSE_SUCCESS',
            'Mahasiswa berhasil memperbarui data pendaftaran mata kuliah',
            [
                'student_number'    => $studentNumber,
                'taken_course_code' => $takenCourseCode,
                'old_data'          => [
                    'course_id' => $oldCourseId,
                    'grade'     => $oldGrade,
                ],
                'new_data'          => $updateData,
            ],
            'student'
        );

        return redirect()->to('/student/taken-courses')->with('success', 'Data mata kuliah berhasil diperbarui.');
    }

    public function delete($takenCourseCode)
    {
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');
        $student = $this->userModel->getStudentProfile($userId);

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        if (isset($student->verification_status) && $student->verification_status === 'completed') {
            return redirect()->back()->with('error', 'Akses ditolak: Pendaftaran Anda telah terverifikasi dan data telah terkunci.');
        }

        $taken = $this->takenCourseModel
            ->where('taken_course_code', $takenCourseCode)
            ->where('user_id', $userId)
            ->first();

        if (!$taken) {
            $this->logActivity(
                'FAILED_DELETE_TAKEN_COURSE_NOT_FOUND',
                'Gagal menghapus mata kuliah karena data tidak ditemukan atau akses ditolak',
                [
                    'student_number'    => $studentNumber,
                    'taken_course_code' => $takenCourseCode,
                ],
                'student'
            );

            return redirect()->to('/student/taken-courses')->with('error', 'Data tidak ditemukan atau akses ditolak.');
        }

        $this->takenCourseModel->delete($taken->id);
        $this->logActivity(
            'DELETE_TAKEN_COURSE_SUCCESS',
            'Mahasiswa berhasil menghapus mata kuliah dari daftar pendaftaran',
            [
                'student_number'    => $studentNumber,
                'taken_course_code' => $takenCourseCode,
                'deleted_data'      => [
                    'course_id' => $taken->course_id,
                    'grade'     => $taken->grade,
                ],
            ],
            'student'
        );

        return redirect()->to('/student/taken-courses')->with('success', 'Mata kuliah berhasil dihapus dari daftar.');
    }
}
