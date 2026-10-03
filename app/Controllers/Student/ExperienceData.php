<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\UserExperienceModel;
use App\Models\UserModel;
use App\Models\TakenCourseModel;
use App\Models\UserDocumentModel;

class ExperienceData extends BaseController
{
    protected $experienceModel;
    protected $userModel;
    protected $takenCourseModel;
    protected $userDocumentModel;

    public function __construct()
    {
        $this->experienceModel   = new UserExperienceModel();
        $this->userModel         = new UserModel();
        $this->takenCourseModel  = new TakenCourseModel();
        $this->userDocumentModel = new UserDocumentModel();
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

        $student     = $this->userModel->getStudentProfile($registrationMain);
        $experiences = $this->experienceModel->getByUserParams($registrationMain);

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

        $takenCoursesCount = $this->takenCourseModel->countUserTakenCourses($registrationMain);
        $hasTakenCourses   = ($takenCoursesCount > 0);

        $document = $this->userDocumentModel->getDocumentByUserParams($registrationMain);
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
            'title'             => 'Pengalaman & Portofolio',
            'student'           => $student,
            'experiences'       => $experiences,
            'profileIncomplete' => $profileIncomplete,
            'canVerify'         => $canVerify,
            'activeTab'         => 'experience',
        ];

        return view('student/experience/index', $data);
    }

    public function store()
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

        if (isset($student->verification_status) && $student->verification_status === 'completed') {
            return redirect()->back()->with('error', 'Akses ditolak: Pendaftaran Anda telah terverifikasi dan data telah terkunci.');
        }

        $validationRules = [
            'title' => [
                'rules'  => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required'   => 'Judul peran atau posisi wajib diisi.',
                    'min_length' => 'Judul minimal berisi 3 karakter.',
                    'max_length' => 'Judul maksimal berisi 255 karakter.',
                ],
            ],
            'organization_name' => [
                'rules'  => 'required|min_length[2]|max_length[255]',
                'errors' => [
                    'required'   => 'Nama instansi atau organisasi wajib diisi.',
                    'min_length' => 'Nama instansi minimal berisi 2 karakter.',
                    'max_length' => 'Nama instansi maksimal berisi 255 karakter.',
                ],
            ],
            'experience_type' => [
                'rules'  => 'required|in_list[work,organizational,teaching_assistant,volunteering,other]',
                'errors' => [
                    'required' => 'Jenis pengalaman wajib dipilih.',
                    'in_list'  => 'Pilihan jenis pengalaman tidak valid.',
                ],
            ],
            'year_occurred' => [
                'rules'  => 'required|valid_date[Y]|greater_than[1990]|less_than_equal_to[' . date('Y') . ']',
                'errors' => [
                    'required'           => 'Tahun pelaksanaan wajib diisi.',
                    'valid_date'          => 'Format tahun pelaksanaan harus berupa 4 digit angka tahun (YYYY).',
                    'greater_than'        => 'Tahun pelaksanaan harus lebih besar dari tahun 1990.',
                    'less_than_equal_to'  => 'Tahun pelaksanaan tidak boleh melebihi tahun saat ini.',
                ],
            ],
            'is_current' => [
                'rules'  => 'permit_empty|in_list[0,1]',
                'errors' => [
                    'in_list' => 'Status aktif tidak valid.',
                ],
            ],
            'description' => [
                'rules'  => 'permit_empty|string',
                'errors' => [
                    'string' => 'Deskripsi kegiatan harus berupa teks.',
                ],
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data pengalaman. Silakan periksa kembali form Anda.')->with('errors', $this->validator->getErrors());
        }


        $dateTimeNow     = date('Ymd_His');
        $isCurrent       = $this->request->getPost('is_current') ? 1 : 0;
        $randomCharacter = strtoupper(bin2hex(random_bytes(16)));
        $experienceMain  = "fa-experience_{$studentNumber}_{$randomCharacter}_{$dateTimeNow}";

        $saveData = [
            'user_params'       => $registrationMain,
            'experience_main'   => $experienceMain,
            'title'             => $this->request->getPost('title'),
            'organization_name' => $this->request->getPost('organization_name'),
            'experience_type'   => $this->request->getPost('experience_type'),
            'year_occurred'     => $this->request->getPost('year_occurred'),
            'is_current'        => $isCurrent,
            'description'       => $this->request->getPost('description'),
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ];

        $this->experienceModel->insert($saveData);
        $insertId = $this->experienceModel->getInsertID();

        $this->logActivity(
            'CREATE_EXPERIENCE_SUCCESS',
            'Mahasiswa berhasil menambahkan pengalaman baru: ' . $saveData['title'],
            [
                'registration_main' => $registrationMain,
                'student_number'    => $studentNumber,
                'experience_id'     => $insertId,
                'experience_main'   => $experienceMain,
                'data'              => $saveData,
            ],
            'student'
        );

        return redirect()->to('/student/experiences')->with('success', 'Data pengalaman berhasil ditambahkan.');
    }

    public function update($experienceMain)
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

        if (isset($student->verification_status) && $student->verification_status === 'completed') {
            return redirect()->back()->with('error', 'Akses ditolak: Pendaftaran Anda telah terverifikasi dan data telah terkunci.');
        }

        $experience = $this->experienceModel
            ->where('experience_main', $experienceMain)
            ->where('user_params', $registrationMain)
            ->first();

        if (!$experience) {
            return redirect()->to('/student/experiences')->with('error', 'Data tidak ditemukan atau akses ditolak.');
        }

        $validationRules = [
            'title' => [
                'rules'  => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required'   => 'Judul peran atau posisi wajib diisi.',
                    'min_length' => 'Judul minimal berisi 3 karakter.',
                    'max_length' => 'Judul maksimal berisi 255 karakter.',
                ],
            ],
            'organization_name' => [
                'rules'  => 'required|min_length[2]|max_length[255]',
                'errors' => [
                    'required'   => 'Nama instansi atau organisasi wajib diisi.',
                    'min_length' => 'Nama instansi minimal berisi 2 karakter.',
                    'max_length' => 'Nama instansi maksimal berisi 255 karakter.',
                ],
            ],
            'experience_type' => [
                'rules'  => 'required|in_list[work,organizational,teaching_assistant,volunteering,other]',
                'errors' => [
                    'required' => 'Jenis pengalaman wajib dipilih.',
                    'in_list'  => 'Pilihan jenis pengalaman tidak valid.',
                ],
            ],
            'year_occurred' => [
                'rules'  => 'required|valid_date[Y]|greater_than[1990]|less_than_equal_to[' . date('Y') . ']',
                'errors' => [
                    'required'           => 'Tahun pelaksanaan wajib diisi.',
                    'valid_date'          => 'Format tahun pelaksanaan harus berupa 4 digit angka tahun (YYYY).',
                    'greater_than'        => 'Tahun pelaksanaan harus lebih besar dari tahun 1990.',
                    'less_than_equal_to'  => 'Tahun pelaksanaan tidak boleh melebihi tahun saat ini.',
                ],
            ],
            'is_current' => [
                'rules'  => 'permit_empty|in_list[0,1]',
                'errors' => [
                    'in_list' => 'Status aktif tidak valid.',
                ],
            ],
            'description' => [
                'rules'  => 'permit_empty|string',
                'errors' => [
                    'string' => 'Deskripsi kegiatan harus berupa teks.',
                ],
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data pengalaman. Silakan periksa kembali form Anda.')->with('errors', $this->validator->getErrors());
        }

        $isCurrent = $this->request->getPost('is_current') ? 1 : 0;

        $newData = [
            'title'             => trim((string)$this->request->getPost('title')),
            'organization_name' => trim((string)$this->request->getPost('organization_name')),
            'experience_type'   => (string)$this->request->getPost('experience_type'),
            'year_occurred'     => (int)$this->request->getPost('year_occurred'),
            'is_current'        => (int)$isCurrent,
            'description'       => trim((string)$this->request->getPost('description')),
        ];

        $isChanged     = false;
        $changedFields = [];

        foreach ($newData as $field => $value) {
            $oldValue = is_object($experience) ? ($experience->$field ?? null) : ($experience[$field] ?? null);

            if (in_array($field, ['year_occurred', 'is_current'])) {
                $oldValue = (int)$oldValue;
            } elseif (is_string($oldValue)) {
                $oldValue = trim($oldValue);
            }

            if ($oldValue !== $value) {
                $isChanged     = true;
                $changedFields[] = $field;
            }
        }

        if (!$isChanged) {
            return redirect()
                ->to('/student/experiences')
                ->with('info', 'Tidak ada perubahan pada data pengalaman.');
        }

        $newData['updated_at'] = date('Y-m-d H:i:s');

        $this->experienceModel
            ->where('experience_main', $experienceMain)
            ->where('user_params', $registrationMain)
            ->set($newData)
            ->update();

        $this->logActivity(
            'UPDATE_EXPERIENCE_SUCCESS',
            'Mahasiswa berhasil memperbarui data pengalaman (' . implode(', ', $changedFields) . ') Code: ' . $experienceMain,
            [
                'registration_main' => $registrationMain,
                'student_number'    => $studentNumber,
                'experience_main'   => $experienceMain,
                'updated_fields'    => $changedFields,
                'updated_data'      => $newData,
            ],
            'student'
        );

        return redirect()->to('/student/experiences')->with('success', 'Data pengalaman berhasil diperbarui.');
    }

    public function delete($experienceMain)
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

        if (isset($student->verification_status) && $student->verification_status === 'completed') {
            return redirect()->back()->with('error', 'Akses ditolak: Pendaftaran Anda telah terverifikasi dan data telah terkunci.');
        }

        $experience = $this->experienceModel
            ->where('experience_main', $experienceMain)
            ->where('user_params', $registrationMain)
            ->first();

        if (!$experience) {
            return redirect()->to('/student/experiences')->with('error', 'Data tidak ditemukan atau akses ditolak.');
        }

        $this->experienceModel
            ->where('experience_main', $experienceMain)
            ->where('user_params', $registrationMain)
            ->delete();

        $experienceTitle = is_object($experience) ? $experience->title : ($experience['title'] ?? '');

        $this->logActivity(
            'DELETE_EXPERIENCE_SUCCESS',
            'Mahasiswa menghapus pengalaman: ' . $experienceTitle,
            [
                'registration_main' => $registrationMain,
                'student_number'    => $studentNumber,
                'experience_main'   => $experienceMain,
            ],
            'student'
        );

        return redirect()->to('/student/experiences')->with('success', 'Data pengalaman berhasil dihapus.');
    }
}
