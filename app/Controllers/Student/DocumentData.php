<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\UserDocumentModel;
use App\Models\UserModel;
use App\Models\TakenCourseModel;
use App\Models\SystemEventSettingModel;

class DocumentData extends BaseController
{
    protected $userDocumentModel;
    protected $userModel;
    protected $takenCourseModel;
    protected $systemEventModel;

    public function __construct()
    {
        $this->userDocumentModel = new UserDocumentModel();
        $this->userModel         = new UserModel();
        $this->takenCourseModel = new TakenCourseModel();
        $this->systemEventModel  = new SystemEventSettingModel();
    }

    private function isRegistrationOpen(): bool
    {
        $activeEvent = $this->systemEventModel->getActiveRecruitmentEvent();

        if (!$activeEvent || empty($activeEvent['event_key'])) {
            return false;
        }

        return $this->systemEventModel->isEventCurrentlyOpen($activeEvent['event_key']);
    }

    private function checkEligibility($registrationMain)
    {
        $student = $this->userModel->getStudentProfile($registrationMain);

        if (!$student) {
            return [
                'eligible' => false,
                'message'  => 'Gagal memproses berkas. Data mahasiswa tidak ditemukan.'
            ];
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

        if ($profileIncomplete) {
            return [
                'eligible' => false,
                'message'  => 'Gagal memproses berkas. Data profil Anda belum lengkap.'
            ];
        }

        $takenCoursesCount = $this->takenCourseModel->countUserTakenCourses($registrationMain);
        if ($takenCoursesCount === 0) {
            return [
                'eligible' => false,
                'message'  => 'Gagal memproses berkas. Anda belum mengambil mata kuliah.'
            ];
        }

        return ['eligible' => true];
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

        $isEventOpen = $this->isRegistrationOpen();
        $isAlreadyVerified = isset($student->verification_status) && $student->verification_status === 'completed';
        $canVerify         = (!$profileIncomplete && $hasTakenCourses && $allDocumentsUploaded && !$isAlreadyVerified);
        $activeEvent = $this->systemEventModel->getActiveRecruitmentEvent();

        $data = [
            'title'             => 'Upload Berkas Pendaftaran',
            'student'           => $student,
            'document'          => $document,
            'profileIncomplete' => $profileIncomplete,
            'hasTakenCourses'   => $hasTakenCourses,
            'canVerify'         => $canVerify,
            'isEventOpen'       => $isEventOpen,
            'active_event'      => $activeEvent,
            'activeTab'         => 'user_documents',
        ];

        return view('student/documents/index', $data);
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

        if (!$this->isRegistrationOpen()) {
            return redirect()->back()->withInput()->with('error', 'Akses ditolak: Periode pendaftaran calon anggota asisten praktikum ditutup.');
        }

        if (isset($student->verification_status) && $student->verification_status === 'completed') {
            return redirect()->back()->with('error', 'Akses ditolak: Pendaftaran Anda telah terverifikasi dan data telah terkunci.');
        }

        $check = $this->checkEligibility($registrationMain);
        if (!$check['eligible']) {
            return redirect()->to('/student/documents')->with('error', $check['message']);
        }

        $pdfValidationRule = 'uploaded[{field}]|max_size[{field},3072]|ext_in[{field},pdf]|mime_in[{field},application/pdf,application/x-pdf]';

        $rules = [
            'student_card_file' => [
                'rules'  => str_replace('{field}', 'student_card_file', $pdfValidationRule),
                'errors' => [
                    'uploaded' => 'Kartu Tanda Mahasiswa (KTM) wajib diunggah.',
                    'max_size' => 'Ukuran berkas KTM maksimal 3MB.',
                    'ext_in'   => 'Format berkas KTM harus berupa PDF.',
                    'mime_in'  => 'Berkas KTM yang diunggah terdeteksi bukan PDF yang valid.',
                ]
            ],
            'application_letter_file' => [
                'rules'  => str_replace('{field}', 'application_letter_file', $pdfValidationRule),
                'errors' => [
                    'uploaded' => 'Surat Permohonan wajib diunggah.',
                    'max_size' => 'Ukuran berkas Surat Permohonan maksimal 3MB.',
                    'ext_in'   => 'Format Surat Permohonan harus berupa PDF.',
                    'mime_in'  => 'Berkas Surat Permohonan terdeteksi bukan PDF yang valid.',
                ]
            ],
            'cv_file' => [
                'rules'  => str_replace('{field}', 'cv_file', $pdfValidationRule),
                'errors' => [
                    'uploaded' => 'Curriculum Vitae (CV) wajib diunggah.',
                    'max_size' => 'Ukuran CV maksimal 3MB.',
                    'ext_in'   => 'Format CV harus berupa PDF.',
                    'mime_in'  => 'Berkas CV terdeteksi bukan PDF yang valid.',
                ]
            ],
            'latest_transcript_file' => [
                'rules'  => str_replace('{field}', 'latest_transcript_file', $pdfValidationRule),
                'errors' => [
                    'uploaded' => 'Transkrip Nilai Terakhir wajib diunggah.',
                    'max_size' => 'Ukuran Transkrip Nilai maksimal 3MB.',
                    'ext_in'   => 'Format Transkrip Nilai harus berupa PDF.',
                    'mime_in'  => 'Berkas Transkrip Nilai terdeteksi bukan PDF yang valid.',
                ]
            ],
            'statement_letter_file' => [
                'rules'  => str_replace('{field}', 'statement_letter_file', $pdfValidationRule),
                'errors' => [
                    'uploaded' => 'Surat Pernyataan wajib diunggah.',
                    'max_size' => 'Ukuran Surat Pernyataan maksimal 3MB.',
                    'ext_in'   => 'Format Surat Pernyataan harus berupa PDF.',
                    'mime_in'  => 'Berkas Surat Pernyataan terdeteksi bukan PDF yang valid.',
                ]
            ],
            'registration_form_file' => [
                'rules'  => str_replace('{field}', 'registration_form_file', $pdfValidationRule),
                'errors' => [
                    'uploaded' => 'Formulir Pendaftaran wajib diunggah.',
                    'max_size' => 'Ukuran Formulir Pendaftaran maksimal 3MB.',
                    'ext_in'   => 'Format Formulir Pendaftaran harus berupa PDF.',
                    'mime_in'  => 'Berkas Formulir Pendaftaran terdeteksi bukan PDF yang valid.',
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $folderDate = date('Y-m-d');
        $uploadDir  = WRITEPATH . "uploads/document_file/{$studentNumber}_{$folderDate}_{$registrationMain}/";

        ensure_secure_directory($uploadDir);
        $randomCharacter = strtoupper(bin2hex(random_bytes(16)));
        $dateTimeNow     = date('YmdHis');
        $documentMain = "fa-user-document_{$studentNumber}_{$randomCharacter}_{$dateTimeNow}";

        $files = [
            'student_card_file'       => ['file' => $this->request->getFile('student_card_file'),       'prefix' => 'KTM'],
            'application_letter_file' => ['file' => $this->request->getFile('application_letter_file'), 'prefix' => 'SL'],
            'cv_file'                 => ['file' => $this->request->getFile('cv_file'),                 'prefix' => 'CV'],
            'latest_transcript_file'  => ['file' => $this->request->getFile('latest_transcript_file'),  'prefix' => 'TNT'],
            'statement_letter_file'   => ['file' => $this->request->getFile('statement_letter_file'),   'prefix' => 'SP'],
            'registration_form_file'  => ['file' => $this->request->getFile('registration_form_file'),  'prefix' => 'FP'],
        ];

        $saveData = [
            'document_main' => $documentMain,
            'user_params'   => $registrationMain,
        ];

        foreach ($files as $field => $item) {
            $file   = $item['file'];
            $prefix = $item['prefix'];

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $this->generateSecureFileName($prefix, $studentNumber);
                $file->move($uploadDir, $newName);
                $saveData[$field] = "{$studentNumber}_{$folderDate}_{$registrationMain}/" . $newName;
            }
        }

        $this->userDocumentModel->insert($saveData);

        $this->logActivity(
            'UPLOAD_DOCUMENTS_SUCCESS',
            'Mahasiswa berhasil mengunggah seluruh berkas pendaftaran',
            [
                'student_number' => $studentNumber,
                'document_main'  => $documentMain,
            ],
            'student'
        );

        return redirect()->to('/student/documents')->with('success', 'Seluruh berkas pendaftaran berhasil diunggah.');
    }

    public function update($documentMain)
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

        if (!$this->isRegistrationOpen()) {
            return redirect()->back()->withInput()->with('error', 'Akses ditolak: Periode pendaftaran calon anggota asisten praktikum ditutup.');
        }

        if (isset($student->verification_status) && $student->verification_status === 'completed') {
            return redirect()->back()->with('error', 'Akses ditolak: Pendaftaran Anda telah terverifikasi dan data telah terkunci.');
        }

        $check = $this->checkEligibility($registrationMain);
        if (!$check['eligible']) {
            return redirect()->to('/student/documents')->with('error', $check['message']);
        }

        $document = $this->userDocumentModel->getDocumentByCodeAndUser($documentMain, $registrationMain);

        if (!$document) {
            return redirect()->to('/student/documents')->with('error', 'Dokumen tidak ditemukan atau akses ditolak.');
        }

        $folderDate = date('Y-m-d');
        $uploadDir  = WRITEPATH . "uploads/document_file/{$studentNumber}_{$folderDate}_{$registrationMain}";
        ensure_secure_directory($uploadDir);

        $fileConfigs = [
            'student_card_file'       => 'KTM',
            'application_letter_file' => 'SL',
            'cv_file'                 => 'CV',
            'latest_transcript_file'  => 'TNT',
            'statement_letter_file'   => 'SP',
            'registration_form_file'  => 'FP',
        ];

        $updateData = [];
        $rules      = [];

        foreach ($fileConfigs as $field => $prefix) {
            $file = $this->request->getFile($field);
            if ($file && $file->isValid()) {
                $rules[$field] = [
                    'rules'  => "max_size[{$field},3072]|ext_in[{$field},pdf]|mime_in[{$field},application/pdf,application/x-pdf]",
                    'errors' => [
                        'max_size' => "Ukuran berkas maksimal 3MB.",
                        'ext_in'   => "Format berkas harus berupa PDF.",
                        'mime_in'  => "Berkas terdeteksi bukan PDF yang valid.",
                    ]
                ];
            }
        }

        if (!empty($rules) && !$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $hasChanged = false;
        foreach ($fileConfigs as $field => $prefix) {
            $file = $this->request->getFile($field);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                if (!empty($document->$field) && file_exists(WRITEPATH . 'uploads/document_file/' . $document->$field)) {
                    @unlink(WRITEPATH . 'uploads/document_file/' . $document->$field);
                }

                $newName = $this->generateSecureFileName($prefix, $studentNumber);
                $file->move($uploadDir, $newName);
                $updateData[$field] = "{$studentNumber}_{$folderDate}_{$registrationMain}/" . $newName;
                $hasChanged = true;
            }
        }

        if (!$hasChanged) {
            return redirect()->to('/student/documents')->with('info', 'Tidak ada berkas yang diperbarui.');
        }

        $updateData['updated_at'] = date('Y-m-d H:i:s');
        $this->userDocumentModel->where('document_main', $documentMain)->set($updateData)->update();

        $this->logActivity(
            'UPDATE_DOCUMENTS_SUCCESS',
            'Mahasiswa memperbarui berkas pendaftaran',
            [
                'student_number' => $studentNumber,
                'document_main'  => $documentMain,
            ],
            'student'
        );

        return redirect()->to('/student/documents')->with('success', 'Berkas pendaftaran berhasil diperbarui.');
    }

    public function reset($documentMain)
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

        if (!$this->isRegistrationOpen()) {
            return redirect()->back()->withInput()->with('error', 'Akses ditolak: Periode pendaftaran calon anggota asisten praktikum ditutup.');
        }

        if (isset($student->verification_status) && $student->verification_status === 'completed') {
            return redirect()->back()->with('error', 'Akses ditolak: Pendaftaran Anda telah terverifikasi dan data telah terkunci.');
        }

        $check = $this->checkEligibility($registrationMain);
        if (!$check['eligible']) {
            return redirect()->to('/student/documents')->with('error', $check['message']);
        }

        $document = $this->userDocumentModel->getDocumentByCodeAndUser($documentMain, $registrationMain);

        if (!$document) {
            return redirect()->to('/student/documents')->with('error', 'Dokumen tidak ditemukan atau akses ditolak.');
        }

        $fileFields = [
            'student_card_file',
            'application_letter_file',
            'cv_file',
            'latest_transcript_file',
            'statement_letter_file',
            'registration_form_file',
        ];

        $folderPath = null;

        foreach ($fileFields as $field) {
            if (!empty($document->$field)) {
                $filePath = WRITEPATH . 'uploads/document_file/' . $document->$field;
                if (!$folderPath) {
                    $folderPath = dirname($filePath);
                }

                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
        }

        $baseUploadDir = realpath(WRITEPATH . 'uploads/document_file');

        if ($folderPath && is_dir($folderPath)) {
            $realFolderPath = realpath($folderPath);
            if ($realFolderPath && $baseUploadDir && $realFolderPath !== $baseUploadDir && strpos($realFolderPath, $baseUploadDir) === 0) {
                @rmdir($realFolderPath);
            }
        }

        $this->userDocumentModel->where('document_main', $documentMain)->delete();

        $this->logActivity(
            'RESET_DOCUMENTS_SUCCESS',
            'Mahasiswa berhasil mereset seluruh berkas pendaftaran',
            [
                'student_number' => $studentNumber,
                'document_main'  => $documentMain,
            ],
            'student'
        );

        return redirect()->to('/student/documents')->with('success', 'Seluruh berkas dan folder pendaftaran berhasil direset.');
    }

    private function generateSecureFileName(string $prefix, string $studentNumber): string
    {
        $dateNow      = date('YmdHis');
        $random32Char = bin2hex(random_bytes(16));

        return "{$prefix}_{$studentNumber}_{$dateNow}_{$random32Char}.pdf";
    }
}
