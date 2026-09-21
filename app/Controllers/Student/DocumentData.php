<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\UserDocumentModel;
use App\Models\UserModel;
use App\Models\TakenCourseModel;

class DocumentData extends BaseController
{
    protected $userDocumentModel;
    protected $userModel;
    protected $takenCourseModel;

    public function __construct()
    {
        $this->userDocumentModel = new UserDocumentModel();
        $this->userModel        = new UserModel();
        $this->takenCourseModel = new TakenCourseModel();
    }

    private function checkEligibility($userId)
    {
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
            'address',
            'gpa',
            'profile',
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

        if ($profileIncomplete) {
            return [
                'eligible' => false,
                'message'  => 'Gagal memproses berkas. Data profil Anda belum lengkap.'
            ];
        }

        $takenCoursesCount = $this->takenCourseModel->countUserTakenCourses($userId);
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
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $student = $this->userModel->getStudentProfile($userId);
        $profileIncomplete = false;

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
            'address',
            'gpa',
            'profile',
        ];

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
        $hasNoTakenCourses = ($takenCoursesCount === 0);

        $document = $this->userDocumentModel->getDocumentByUserId($userId);
        $this->logActivity(
            'VIEW_USER_DOCUMENTS',
            'Mahasiswa melihat halaman kelengkapan berkas pendaftaran',
            [
                'student_number'     => $studentNumber,
                'profile_incomplete' => $profileIncomplete,
                'has_taken_courses'  => !$hasNoTakenCourses,
            ],
            'student'
        );

        $data = [
            'title'             => 'Upload Berkas Pendaftaran',
            'student'           => $student,
            'document'          => $document,
            'profileIncomplete' => $profileIncomplete,
            'hasNoTakenCourses' => $hasNoTakenCourses,
            'activeTab'         => 'user_documents',
        ];

        return view('student/documents/index', $data);
    }

    public function store()
    {
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $check = $this->checkEligibility($userId);
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
        $uploadDir  = WRITEPATH . "uploads/document_file/{$studentNumber}_{$folderDate}/";

        ensure_secure_directory($uploadDir);
        $random10Digits = str_pad(random_int(0, 9999999999), 10, '0', STR_PAD_LEFT);
        $documentCode   = "{$random10Digits}_{$studentNumber}_" . date('YmdHis');

        $files = [
            'student_card_file'       => ['file' => $this->request->getFile('student_card_file'),       'prefix' => 'KTM'],
            'application_letter_file' => ['file' => $this->request->getFile('application_letter_file'), 'prefix' => 'SL'],
            'cv_file'                 => ['file' => $this->request->getFile('cv_file'),                 'prefix' => 'CV'],
            'latest_transcript_file'  => ['file' => $this->request->getFile('latest_transcript_file'),  'prefix' => 'TNT'],
            'statement_letter_file'   => ['file' => $this->request->getFile('statement_letter_file'),   'prefix' => 'SP'],
            'registration_form_file'  => ['file' => $this->request->getFile('registration_form_file'),  'prefix' => 'FP'],
        ];

        $saveData = [
            'document_code' => $documentCode,
            'user_id'       => $userId,
        ];

        foreach ($files as $field => $item) {
            $file   = $item['file'];
            $prefix = $item['prefix'];

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $this->generateSecureFileName($prefix, $studentNumber);
                $file->move($uploadDir, $newName);
                $saveData[$field] = "{$studentNumber}_{$folderDate}/" . $newName;
            }
        }

        $this->userDocumentModel->insert($saveData);

        $this->logActivity(
            'UPLOAD_DOCUMENTS_SUCCESS',
            'Mahasiswa berhasil mengunggah seluruh berkas pendaftaran',
            [
                'student_number' => $studentNumber,
                'document_code'  => $documentCode,
            ],
            'student'
        );

        return redirect()->to('/student/documents')->with('success', 'Seluruh berkas pendaftaran berhasil diunggah.');
    }

    public function update($documentCode)
    {
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $check = $this->checkEligibility($userId);
        if (!$check['eligible']) {
            return redirect()->to('/student/documents')->with('error', $check['message']);
        }

        $document = $this->userDocumentModel->getDocumentByCodeAndUser($documentCode, $userId);

        if (!$document) {
            return redirect()->to('/student/documents')->with('error', 'Dokumen tidak ditemukan atau akses ditolak.');
        }

        $folderDate = date('Y-m-d');
        $uploadDir  = WRITEPATH . "uploads/document_file/{$studentNumber}_{$folderDate}/";
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
                $updateData[$field] = "{$studentNumber}_{$folderDate}/" . $newName;
                $hasChanged = true;
            }
        }

        if (!$hasChanged) {
            return redirect()->to('/student/documents')->with('info', 'Tidak ada berkas yang diperbarui.');
        }

        $updateData['updated_at'] = date('Y-m-d H:i:s');
        $this->userDocumentModel->update($document->id, $updateData);

        $this->logActivity(
            'UPDATE_DOCUMENTS_SUCCESS',
            'Mahasiswa memperbarui berkas pendaftaran',
            [
                'student_number' => $studentNumber,
                'document_code'  => $documentCode,
            ],
            'student'
        );

        return redirect()->to('/student/documents')->with('success', 'Berkas pendaftaran berhasil diperbarui.');
    }

    public function reset($documentCode)
    {
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $check = $this->checkEligibility($userId);
        if (!$check['eligible']) {
            return redirect()->to('/student/documents')->with('error', $check['message']);
        }

        $document = $this->userDocumentModel->getDocumentByCodeAndUser($documentCode, $userId);

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

        foreach ($fileFields as $field) {
            if (!empty($document->$field)) {
                $filePath = WRITEPATH . 'uploads/document_file/' . $document->$field;
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
        }

        $this->userDocumentModel->delete($document->id);
        $this->logActivity(
            'RESET_DOCUMENTS_SUCCESS',
            'Mahasiswa berhasil mereset seluruh berkas pendaftaran',
            [
                'student_number' => $studentNumber,
                'document_code'  => $documentCode,
            ],
            'student'
        );

        return redirect()->to('/student/documents')->with('success', 'Seluruh berkas pendaftaran berhasil direset.');
    }

    private function generateSecureFileName(string $prefix, string $studentNumber): string
    {
        $dateNow      = date('YmdHis');
        $random32Char = bin2hex(random_bytes(16));

        return "{$prefix}_{$studentNumber}_{$dateNow}_{$random32Char}.pdf";
    }
}
