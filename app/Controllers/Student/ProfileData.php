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
        $this->userModel         = new UserModel();
        $this->takenCourseModel  = new TakenCourseModel();
        $this->userDocumentModel = new UserDocumentModel();
        $this->db                = \Config\Database::connect();
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

        $takenCoursesCount    = $this->takenCourseModel->countUserTakenCourses($registrationMain);
        $hasTakenCourses      = ($takenCoursesCount > 0);
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
        $canVerify          = (!$profileIncomplete && $hasTakenCourses && $allDocumentsUploaded && !$isAlreadyVerified);

        $selectedFacultyParams = old('faculty_params', $student->faculty_params ?? null);
        $selectedProgramParams = old('study_program_params', $student->study_program_params ?? null);

        $faculties = $this->db->table('faculties')->get()->getResult();

        $studyPrograms = [];
        if (!empty($selectedFacultyParams)) {
            $studyPrograms = $this->db->table('study_programs')
                ->where('faculty_params', $selectedFacultyParams)
                ->get()->getResult();
        }

        $classGroups = [];
        if (!empty($selectedProgramParams)) {
            $classGroups = $this->db->table('class_groups')
                ->where('study_program_params', $selectedProgramParams)
                ->where('is_active', 1)
                ->get()->getResult();
        }

        $activeTab = filter_var($this->request->getGet('tab'), FILTER_SANITIZE_SPECIAL_CHARS) ?: 'biodata';

        $data = [
            'title'         => 'Profil & Biodata Mahasiswa',
            'student'       => $student,
            'faculties'     => $faculties,
            'studyPrograms' => $studyPrograms,
            'classGroups'   => $classGroups,
            'activeTab'     => $activeTab,
            'canVerify'     => $canVerify
        ];

        return view('student/profile/index', $data);
    }

    public function getStudyProgramsByFaculty($facultyParams)
    {
        $programs = $this->db->table('study_programs')
            ->select('program_main, program_name, degree_level')
            ->where('faculty_params', $facultyParams)
            ->get()->getResult();

        return $this->response->setJSON($programs);
    }

    public function getClassGroupsByStudyProgram($studyProgramParams)
    {
        $classes = $this->db->table('class_groups')
            ->select('class_main, class_name')
            ->where('study_program_params', $studyProgramParams)
            ->where('is_active', 1)
            ->get()->getResult();

        return $this->response->setJSON($classes);
    }

    public function updateBiodata()
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

        if (isset($student->verification_status) && $student->verification_status === 'completed') {
            return redirect()->back()->with('error', 'Akses ditolak: Pendaftaran Anda telah terverifikasi dan data telah terkunci.');
        }

        $rules = [
            'full_name'            => 'required|min_length[3]|max_length[255]',
            'phone_number'         => 'required|min_length[10]|max_length[24]',
            'place_of_birth'       => 'required|max_length[100]',
            'date_of_birth'        => 'required|valid_date',
            'gender'               => 'required|in_list[male,female]',
            'faculty_params'       => 'required|is_not_unique[faculties.faculty_main]',
            'study_program_params' => 'required|required_with[faculty_params]|is_not_unique[study_programs.program_main]',
            'class_params'         => 'required|required_with[study_program_params]|is_not_unique[class_groups.class_main]',
            'gpa'                  => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[4]',
            'address'              => 'required|max_length[500]',
            'province'             => 'required|max_length[255]',
            'regency'              => 'required|required_with[province]|max_length[255]',
            'subdistrict'          => 'required|required_with[regency]|max_length[255]',
            'village'              => 'required|required_with[subdistrict]|max_length[255]',
        ];

        $messages = [
            'full_name' => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama minimal 3 karakter.',
            ],
            'phone_number' => [
                'required'   => 'Nomor telepon wajib diisi.',
                'min_length' => 'Nomor telepon minimal 10 angka.',
                'max_length' => 'Nomor telepon maksimal 24 angka.',
            ],
            'place_of_birth' => [
                'required' => 'Tempat lahir wajib diisi.',
            ],
            'date_of_birth' => [
                'required' => 'Tanggal lahir wajib diisi.',
            ],
            'gender' => [
                'required' => 'Jenis kelamin wajib dipilih.',
            ],
            'faculty_params' => [
                'required'      => 'Fakultas wajib dipilih.',
                'is_not_unique' => 'Fakultas tidak valid.',
            ],
            'study_program_params' => [
                'required'      => 'Program studi wajib dipilih.',
                'required_with' => 'Program studi wajib dipilih jika Fakultas diisi.',
                'is_not_unique' => 'Program studi tidak valid.',
            ],
            'class_params' => [
                'required'      => 'Kelas wajib dipilih.',
                'required_with' => 'Kelas wajib dipilih jika Program studi diisi.',
                'is_not_unique' => 'Kelas tidak valid.',
            ],
            'gpa' => [
                'required' => 'IPK wajib diisi.',
                'numeric'  => 'IPK harus berupa angka.',
            ],
            'address' => [
                'required' => 'Alamat lengkap wajib diisi.',
            ],
            'province' => [
                'required' => 'Provinsi wajib diisi.',
            ],
            'regency' => [
                'required'      => 'Kabupaten/Kota wajib diisi.',
                'required_with' => 'Kabupaten/Kota wajib dipilih jika Provinsi diisi.',
            ],
            'subdistrict' => [
                'required'      => 'Kecamatan wajib diisi.',
                'required_with' => 'Kecamatan wajib dipilih jika Kabupaten/Kota diisi.',
            ],
            'village' => [
                'required'      => 'Kelurahan/Desa wajib diisi.',
                'required_with' => 'Kelurahan/Desa wajib dipilih jika Kecamatan diisi.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $this->logActivity(
                'FAILED_UPDATE_BIODATA_VALIDATION',
                'Gagal memperbarui biodata karena kesalahan input form',
                [
                    'registration_main' => $registrationMain,
                    'student_number'    => $studentNumber,
                    'errors'            => $this->validator->getErrors(),
                ],
                'student'
            );

            return redirect()
                ->to(base_url('student/profile?tab=biodata'))
                ->withInput()
                ->with('error', 'Gagal memperbarui biodata. Silakan periksa kembali form Anda.')
                ->with('errors', $this->validator->getErrors());
        }

        $facultyParams      = $this->request->getPost('faculty_params');
        $studyProgramParams = $this->request->getPost('study_program_params');
        $classParams        = $this->request->getPost('class_params');

        $validProgram = $this->db->table('study_programs')
            ->where('program_main', $studyProgramParams)
            ->where('faculty_params', $facultyParams)
            ->countAllResults();

        $validClass = $this->db->table('class_groups')
            ->where('class_main', $classParams)
            ->where('study_program_params', $studyProgramParams)
            ->where('is_active', 1)
            ->countAllResults();

        if (!$validProgram || !$validClass) {
            return redirect()
                ->to(base_url('student/profile?tab=biodata'))
                ->withInput()
                ->with('error', 'Kombinasi Fakultas, Program Studi, atau Kelas tidak valid.');
        }

        $newData = [
            'full_name'            => trim((string)$this->request->getPost('full_name')),
            'phone_number'         => trim((string)$this->request->getPost('phone_number')),
            'place_of_birth'       => trim((string)$this->request->getPost('place_of_birth')),
            'date_of_birth'        => (string)$this->request->getPost('date_of_birth'),
            'gender'               => (string)$this->request->getPost('gender'),
            'faculty_params'       => $facultyParams,
            'study_program_params' => $studyProgramParams,
            'class_params'         => $classParams,
            'gpa'                  => (float)$this->request->getPost('gpa'),
            'province'             => trim((string)$this->request->getPost('province')),
            'regency'              => trim((string)$this->request->getPost('regency')),
            'subdistrict'          => trim((string)$this->request->getPost('subdistrict')),
            'village'              => trim((string)$this->request->getPost('village')),
            'address'              => trim((string)$this->request->getPost('address')),
        ];

        $isChanged     = false;
        $changedFields = [];
        foreach ($newData as $field => $value) {
            $oldValue = $student->$field ?? null;
            if ($field === 'gpa') {
                $oldValue = (float)$oldValue;
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
        $this->userModel->where('registration_main', $registrationMain)->set($newData)->update();

        session()->set([
            'full_name'      => $newData['full_name'],
            'student_number' => $studentNumber,
        ]);

        $this->logActivity(
            'UPDATE_BIODATA_SUCCESS',
            'Mahasiswa berhasil memperbarui biodata profil (' . implode(', ', $changedFields) . ')',
            [
                'registration_main' => $registrationMain,
                'student_number'    => $studentNumber,
                'updated_fields'    => $changedFields,
            ],
            'student'
        );

        return redirect()
            ->to(base_url('student/profile?tab=biodata'))
            ->with('success', 'Biodata profil berhasil diperbarui.');
    }


    public function updatePhoto()
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

        if (isset($student->verification_status) && $student->verification_status === 'completed') {
            return redirect()->back()->with('error', 'Akses ditolak: Pendaftaran Anda telah terverifikasi dan data telah terkunci.');
        }

        $currentProfilePhoto = is_object($student) ? ($student->profile ?? null) : ($student['profile'] ?? null);

        $isRemoved   = $this->request->getPost('remove_profile');
        $base64Image = $this->request->getPost('profile');
        $realImage   = $this->request->getFile('profile_real');

        $baseUploadPath = FCPATH . 'uploads/profile_student/';
        $realUploadPath = FCPATH . 'uploads/profile_student/real/';

        $maxSizeBytes = 1.5 * 1024 * 1024;

        if ($isRemoved == '1') {
            if (!empty($currentProfilePhoto) && !in_array($currentProfilePhoto, ['profile-default.png', 'default.png'], true)) {
                if (file_exists($baseUploadPath . $currentProfilePhoto)) {
                    unlink($baseUploadPath . $currentProfilePhoto);
                }
                if (file_exists($realUploadPath . $currentProfilePhoto)) {
                    unlink($realUploadPath . $currentProfilePhoto);
                }
            }

            $defaultFileName = 'profile-default.png';
            $this->userModel->where('registration_main', $registrationMain)->set(['profile' => $defaultFileName])->update();
            session()->set('profile', $defaultFileName);

            return redirect()
                ->to(base_url('student/profile?tab=photo'))
                ->with('success', 'Foto profil berhasil dikembalikan ke default.');
        }

        if (!empty($base64Image) && str_contains($base64Image, 'data:image')) {

            if (!preg_match('/^data:image\/(png|jpg|jpeg);base64,/', $base64Image)) {

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

            $fileName = 'profile_' . $studentNumber . '_' . $randomString . '_' . $dateTimeNow . '.png';

            if (!empty($currentProfilePhoto) && !in_array($currentProfilePhoto, ['profile-default.png', 'default.png'], true)) {
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

            $this->userModel->where('registration_main', $registrationMain)->set(['profile' => $fileName])->update();
            session()->set('profile', $fileName);

            $this->logActivity(
                'UPDATE_PROFILE_PHOTO_SUCCESS',
                'Mahasiswa berhasil memperbarui pas foto profil',
                [
                    'registration_main' => $registrationMain,
                    'student_number'    => $studentNumber,
                    'file_name'         => $fileName,
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
