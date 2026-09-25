<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\TakenCourseModel;
use App\Models\UserDocumentModel;

class ProfileData extends BaseController
{
    protected $userModel;
    protected $db;
    protected $takenCourseModel;
    protected $userDocumentModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->takenCourseModel = new TakenCourseModel();
        $this->userDocumentModel = new UserDocumentModel();
        $this->db        = \Config\Database::connect();
    }

    public function index()
    {
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $student = $this->userModel->getStudentProfile($userId);

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

        $takenCoursesCount = $this->takenCourseModel->countUserTakenCourses($userId);
        $hasTakenCourses   = ($takenCoursesCount > 0);
        $document = $this->userDocumentModel->getDocumentByUserId($userId);
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
        $canVerify = (!$profileIncomplete && $hasTakenCourses && $allDocumentsUploaded && !$isAlreadyVerified);

        $selectedFacultyId = old('faculty_id', $student->faculty_id ?? null);
        $selectedProgramId = old('study_program_id', $student->study_program_id ?? null);
        $faculties         = $this->db->table('faculties')->get()->getResult();

        $studyPrograms = [];
        if (!empty($selectedFacultyId)) {
            $studyPrograms = $this->db->table('study_programs')
                ->where('faculty_id', $selectedFacultyId)
                ->get()->getResult();
        }

        $classGroups = [];
        if (!empty($selectedProgramId)) {
            $classGroups = $this->db->table('class_groups')
                ->where('study_program_id', $selectedProgramId)
                ->where('is_active', 1)
                ->get()->getResult();
        }

        $activeTab = filter_var($this->request->getGet('tab'), FILTER_SANITIZE_SPECIAL_CHARS) ?: 'biodata';

        $this->logActivity(
            'VIEW_PROFILE',
            'Mahasiswa melihat halaman profil tab: ' . $activeTab,
            [
                'tab'            => $activeTab,
                'student_number' => $studentNumber,
                'can_verify'     => $canVerify,
            ],
            'student'
        );


        $appProfileModel = new \App\Models\CompanyApplicationModel();
        $appProfile = $appProfileModel->first();

        $data = [
            'title'         => 'Profil & Biodata Mahasiswa',
            'student'       => $student,
            'faculties'     => $faculties,
            'studyPrograms' => $studyPrograms,
            'classGroups'   => $classGroups,
            'activeTab'     => $activeTab,
            'canVerify'     => $canVerify,
            'appProfile'        => $appProfile
        ];

        return view('student/profile/index', $data);
    }

    public function getStudyProgramsByFaculty($facultyId)
    {
        $programs = $this->db->table('study_programs')
            ->where('faculty_id', $facultyId)
            ->get()->getResult();

        return $this->response->setJSON($programs);
    }

    public function getClassGroupsByStudyProgram($studyProgramId)
    {
        $classes = $this->db->table('class_groups')
            ->where('study_program_id', $studyProgramId)
            ->where('is_active', 1)
            ->get()->getResult();

        return $this->response->setJSON($classes);
    }

    public function updateBiodata()
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

        $currentUser = $this->userModel->find($userId);
        if (!$currentUser) {
            return redirect()->to('/auth/login')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $rules = [
            'full_name'        => 'required|min_length[3]|max_length[255]',
            'phone_number'     => 'required|min_length[10]|max_length[24]',
            'place_of_birth'   => 'required|max_length[100]',
            'date_of_birth'    => 'required|valid_date',
            'gender'           => 'required|in_list[male,female]',
            'faculty_id'       => 'required|numeric|is_not_unique[faculties.id]',
            'study_program_id' => 'required|required_with[faculty_id]|numeric|is_not_unique[study_programs.id]',
            'class_id'         => 'required|required_with[study_program_id]|numeric|is_not_unique[class_groups.id]',
            'gpa'              => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[4]',
            'address'          => 'required|max_length[500]',
            'province'         => 'required|max_length[255]',
            'regency'          => 'required|required_with[province]|max_length[255]',
            'subdistrict'      => 'required|required_with[regency]|max_length[255]',
            'village'          => 'required|required_with[subdistrict]|max_length[255]',
        ];

        $messages = [
            'full_name'        => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama minimal 3 karakter.',
            ],
            'phone_number'     => [
                'required'   => 'Nomor telepon wajib diisi.',
                'min_length' => 'Nomor telepon minimal 10 angka.',
                'max_length' => 'Nomor telepon maksimal 20 angka.',
            ],
            'place_of_birth'   => [
                'required'   => 'Tempat lahir wajib diisi.',
            ],
            'date_of_birth'    => [
                'required'   => 'Tanggal lahir wajib diisi.',
            ],
            'gender'           => [
                'required'   => 'Jenis kelamin wajib dipilih.',
            ],
            'faculty_id'       => [
                'required'      => 'Fakultas wajib dipilih.',
                'is_not_unique' => 'Fakultas tidak valid.',
            ],
            'study_program_id' => [
                'required'      => 'Program studi wajib dipilih.',
                'required_with' => 'Program studi wajib dipilih jika Fakultas diisi.',
                'is_not_unique' => 'Program studi tidak valid.',
            ],
            'class_id'         => [
                'required'      => 'Kelas wajib dipilih.',
                'required_with' => 'Kelas wajib dipilih jika Program studi diisi.',
                'is_not_unique' => 'Kelas tidak valid.',
            ],
            'gpa'              => [
                'required'   => 'IPK wajib diisi.',
                'numeric'    => 'IPK harus berupa angka.',
            ],
            'address'          => [
                'required'   => 'Alamat lengkap wajib diisi.',
            ],
            'province'         => [
                'required'   => 'Provinsi wajib diisi.',
            ],
            'regency'          => [
                'required'      => 'Kabupaten/Kota wajib diisi.',
                'required_with' => 'Kabupaten/Kota wajib dipilih jika Provinsi diisi.',
            ],
            'subdistrict'      => [
                'required'      => 'Kecamatan wajib diisi.',
                'required_with' => 'Kecamatan wajib dipilih jika Kabupaten/Kota diisi.',
            ],
            'village'          => [
                'required'      => 'Kelurahan/Desa wajib diisi.',
                'required_with' => 'Kelurahan/Desa wajib dipilih jika Kecamatan diisi.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $this->logActivity(
                'FAILED_UPDATE_BIODATA_VALIDATION',
                'Gagal memperbarui biodata karena kesalahan input form',
                [
                    'student_number' => $studentNumber,
                    'errors'         => $this->validator->getErrors(),
                ],
                'student'
            );

            return redirect()
                ->to(base_url('student/profile?tab=biodata'))
                ->withInput()
                ->with('error', 'Gagal memperbarui biodata. Silakan periksa kembali form Anda.')
                ->with('errors', $this->validator->getErrors());
        }

        $facultyId      = $this->request->getPost('faculty_id');
        $studyProgramId = $this->request->getPost('study_program_id');
        $classId        = $this->request->getPost('class_id');

        $validProgram = $this->db->table('study_programs')
            ->where('id', $studyProgramId)
            ->where('faculty_id', $facultyId)
            ->countAllResults();

        $validClass = $this->db->table('class_groups')
            ->where('id', $classId)
            ->where('study_program_id', $studyProgramId)
            ->where('is_active', 1)
            ->countAllResults();

        if (!$validProgram || !$validClass) {
            $this->logActivity(
                'FAILED_UPDATE_BIODATA_INVALID_COMBINATION',
                'Gagal memperbarui biodata karena kombinasi Fakultas, Prodi, atau Kelas tidak valid',
                [
                    'student_number'   => $studentNumber,
                    'faculty_id'       => $facultyId,
                    'study_program_id' => $studyProgramId,
                    'class_id'         => $classId,
                ],
                'student'
            );

            return redirect()
                ->to(base_url('student/profile?tab=biodata'))
                ->withInput()
                ->with('error', 'Kombinasi Fakultas, Program Studi, atau Kelas tidak valid.');
        }

        $newData = [
            'full_name'        => trim((string)$this->request->getPost('full_name')),
            'phone_number'     => trim((string)$this->request->getPost('phone_number')),
            'place_of_birth'   => trim((string)$this->request->getPost('place_of_birth')),
            'date_of_birth'    => (string)$this->request->getPost('date_of_birth'),
            'gender'           => (string)$this->request->getPost('gender'),
            'faculty_id'       => (int)$facultyId,
            'study_program_id' => (int)$studyProgramId,
            'class_id'         => (int)$classId,
            'gpa'              => (float)$this->request->getPost('gpa'),
            'province'         => trim((string)$this->request->getPost('province')),
            'regency'          => trim((string)$this->request->getPost('regency')),
            'subdistrict'      => trim((string)$this->request->getPost('subdistrict')),
            'village'          => trim((string)$this->request->getPost('village')),
            'address'          => trim((string)$this->request->getPost('address')),
        ];

        $isChanged     = false;
        $changedFields = [];
        foreach ($newData as $field => $value) {
            $oldValue = is_object($currentUser) ? ($currentUser->$field ?? null) : ($currentUser[$field] ?? null);
            if ($field === 'gpa') {
                $oldValue = (float)$oldValue;
            } elseif (in_array($field, ['faculty_id', 'study_program_id', 'class_id'])) {
                $oldValue = (int)$oldValue;
            }

            if ($oldValue !== $value) {
                $isChanged       = true;
                $changedFields[] = $field;
            }
        }

        if (!$isChanged) {
            return redirect()
                ->to(base_url('student/profile?tab=biodata'))
                ->with('info', 'Tidak ada perubahan pada biodata diri.');
        }

        $newData['updated_at'] = date('Y-m-d H:i:s');
        $this->userModel->update($userId, $newData);

        session()->set([
            'full_name'      => $newData['full_name'],
            'student_number' => $studentNumber,
        ]);

        $this->logActivity(
            'UPDATE_BIODATA_SUCCESS',
            'Mahasiswa berhasil memperbarui biodata profil (' . implode(', ', $changedFields) . ')',
            [
                'student_number' => $studentNumber,
                'updated_fields' => $changedFields,
            ],
            'student'
        );

        return redirect()
            ->to(base_url('student/profile?tab=biodata'))
            ->with('success', 'Biodata profil berhasil diperbarui.');
    }

    public function updatePhoto()
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

        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->to('/auth/login')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $currentProfilePhoto = is_object($user) ? ($user->profile ?? null) : ($user['profile'] ?? null);

        $isRemoved   = $this->request->getPost('remove_profile');
        $base64Image = $this->request->getPost('profile');
        $realImage   = $this->request->getFile('profile_real');

        $baseUploadPath = FCPATH . 'uploads/profile_student/';
        $realUploadPath = FCPATH . 'uploads/profile_student/real/';

        $maxSizeBytes = 1.5 * 1024 * 1024;

        if ($isRemoved == '1') {
            if (!empty($currentProfilePhoto) && $currentProfilePhoto !== 'profile-default.png') {
                if (file_exists($baseUploadPath . $currentProfilePhoto)) {
                    unlink($baseUploadPath . $currentProfilePhoto);
                }
                if (file_exists($realUploadPath . $currentProfilePhoto)) {
                    unlink($realUploadPath . $currentProfilePhoto);
                }
            }

            $defaultFileName = 'profile-default.png';
            $this->userModel->update($userId, ['profile' => $defaultFileName]);
            session()->set('profile', $defaultFileName);

            $this->logActivity(
                'DELETE_PROFILE_PHOTO_SUCCESS',
                'Mahasiswa menghapus foto profil dan mengembalikan ke foto default',
                [
                    'student_number' => $studentNumber,
                    'previous_file'  => $currentProfilePhoto,
                ],
                'student'
            );

            return redirect()
                ->to(base_url('student/profile?tab=photo'))
                ->with('success', 'Foto profil berhasil dikembalikan ke default.');
        }

        if (!empty($base64Image) && str_contains($base64Image, 'data:image')) {

            if (!preg_match('/^data:image\/(png|jpg|jpeg);base64,/', $base64Image)) {
                $this->logActivity(
                    'FAILED_PHOTO_UPLOAD_INVALID_FORMAT',
                    'Gagal mengunggah foto profil: Format berkas tidak valid',
                    [
                        'student_number' => $studentNumber,
                    ],
                    'student'
                );

                return redirect()
                    ->to(base_url('student/profile?tab=photo'))
                    ->with('error', 'Format gambar harus berupa PNG, JPG, atau JPEG.');
            }

            $data        = substr($base64Image, strpos($base64Image, ',') + 1);
            $decodedData = base64_decode($data);

            if ($decodedData === false) {
                return redirect()
                    ->to(base_url('student/profile?tab=photo'))
                    ->with('error', 'Gagal memproses berkas gambar.');
            }

            if (strlen($decodedData) > $maxSizeBytes) {
                $this->logActivity(
                    'FAILED_PHOTO_UPLOAD_EXCEED_SIZE',
                    'Gagal mengunggah foto profil: Ukuran berkas melebihi 1.5 MB',
                    [
                        'student_number' => $studentNumber,
                        'file_size'      => strlen($decodedData),
                    ],
                    'student'
                );

                return redirect()
                    ->to(base_url('student/profile?tab=photo'))
                    ->with('error', 'Ukuran foto hasil potong melebihi batas maksimal 1,5 MB.');
            }

            if ($realImage && $realImage->isValid() && !$realImage->hasMoved()) {
                if ($realImage->getSize() > $maxSizeBytes) {
                    return redirect()
                        ->to(base_url('student/profile?tab=photo'))
                        ->with('error', 'Ukuran gambar asli yang diunggah tidak boleh lebih dari 1,5 MB.');
                }
            }

            if (!is_dir($baseUploadPath)) {
                mkdir($baseUploadPath, 0755, true);
            }
            if (!is_dir($realUploadPath)) {
                mkdir($realUploadPath, 0755, true);
            }

            $randomString = random_string('alnum', 32);
            $dateTimeNow  = date('Ymd_His');

            $fileName = 'profile_' . $studentNumber . '_' . $randomString . '_' . $dateTimeNow . '-' . $userId . '.png';

            if (!empty($currentProfilePhoto) && $currentProfilePhoto !== 'profile-default.png') {
                if (file_exists($baseUploadPath . $currentProfilePhoto)) {
                    unlink($baseUploadPath . $currentProfilePhoto);
                }
                if (file_exists($realUploadPath . $currentProfilePhoto)) {
                    unlink($realUploadPath . $currentProfilePhoto);
                }
            }

            file_put_contents($baseUploadPath . $fileName, $decodedData);
            if ($realImage && $realImage->isValid() && !$realImage->hasMoved()) {
                $realImage->move($realUploadPath, $fileName);

                \Config\Services::image()
                    ->withFile($realUploadPath . $fileName)
                    ->save($realUploadPath . $fileName, 75);
            }

            $this->userModel->update($userId, ['profile' => $fileName]);
            session()->set('profile', $fileName);

            $this->logActivity(
                'UPDATE_PROFILE_PHOTO_SUCCESS',
                'Mahasiswa berhasil memperbarui pas foto profil',
                [
                    'student_number' => $studentNumber,
                    'file_name'      => $fileName,
                ],
                'student'
            );

            return redirect()
                ->to(base_url('student/profile?tab=photo'))
                ->with('success', 'Pas foto profil berhasil diperbarui.');
        }

        return redirect()
            ->to(base_url('student/profile?tab=photo'))
            ->with('info', 'Tidak ada perubahan pada foto profil.');
    }
}
