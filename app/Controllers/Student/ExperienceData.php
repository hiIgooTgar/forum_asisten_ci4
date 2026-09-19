<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\UserExperienceModel;
use App\Models\UserModel;

class ExperienceData extends BaseController
{
    protected $experienceModel;
    protected $userModel;

    public function __construct()
    {
        $this->experienceModel = new UserExperienceModel();
        $this->userModel        = new UserModel();
    }

    public function index()
    {
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $student     = $this->userModel->getStudentProfile($userId);
        $experiences = $this->experienceModel->getByUserId($userId);

        $this->logActivity(
            'VIEW_EXPERIENCES',
            'Mahasiswa melihat daftar pengalaman',
            ['student_number' => $studentNumber],
            'student'
        );

        $data = [
            'title'       => 'Pengalaman & Portofolio',
            'student'     => $student,
            'experiences' => $experiences,
            'activeTab'   => 'experience',
        ];

        return view('student/experience/index', $data);
    }

    public function store()
    {
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
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
                    'required'            => 'Tahun pelaksanaan wajib diisi.',
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
            $this->logActivity(
                'FAILED_CREATE_EXPERIENCE_VALIDATION',
                'Gagal menambahkan pengalaman baru karena kesalahan input form',
                [
                    'student_number' => $studentNumber,
                    'errors'         => $this->validator->getErrors(),
                ],
                'student'
            );

            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data pengalaman. Silakan periksa kembali form Anda.')->with('errors', $this->validator->getErrors());
        }

        $isCurrent      = $this->request->getPost('is_current') ? 1 : 0;
        $experienceCode = mt_rand(1000000000, 9999999999) . '_' . $studentNumber;

        $saveData = [
            'user_id'           => $userId,
            'experience_code'   => $experienceCode,
            'title'             => $this->request->getPost('title'),
            'organization_name' => $this->request->getPost('organization_name'),
            'experience_type'   => $this->request->getPost('experience_type'),
            'year_occurred'     => $this->request->getPost('year_occurred'),
            'is_current'        => $isCurrent,
            'description'       => $this->request->getPost('description'),
        ];

        $this->experienceModel->insert($saveData);
        $insertId = $this->experienceModel->getInsertID();

        $this->logActivity(
            'CREATE_EXPERIENCE_SUCCESS',
            'Mahasiswa berhasil menambahkan pengalaman baru: ' . $saveData['title'],
            [
                'student_number'  => $studentNumber,
                'experience_id'   => $insertId,
                'experience_code' => $experienceCode,
                'data'            => $saveData,
            ],
            'student'
        );

        return redirect()->to('/student/experiences')->with('success', 'Data pengalaman berhasil ditambahkan.');
    }

    public function update($experienceCode)
    {
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $experience = $this->experienceModel
            ->where('experience_code', $experienceCode)
            ->where('user_id', $userId)
            ->first();

        if (!$experience) {
            $this->logActivity(
                'FAILED_UPDATE_EXPERIENCE_NOT_FOUND',
                'Gagal memperbarui pengalaman: Data tidak ditemukan atau akses ditolak (Code: ' . $experienceCode . ')',
                [
                    'student_number'  => $studentNumber,
                    'experience_code' => $experienceCode,
                ],
                'student'
            );

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
            $this->logActivity(
                'FAILED_UPDATE_EXPERIENCE_VALIDATION',
                'Gagal memperbarui pengalaman karena kesalahan input form (Code: ' . $experienceCode . ')',
                [
                    'student_number'  => $studentNumber,
                    'experience_code' => $experienceCode,
                    'errors'          => $this->validator->getErrors(),
                ],
                'student'
            );

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
            ->where('experience_code', $experienceCode)
            ->set($newData)
            ->update();

        $this->logActivity(
            'UPDATE_EXPERIENCE_SUCCESS',
            'Mahasiswa berhasil memperbarui data pengalaman (' . implode(', ', $changedFields) . ') Code: ' . $experienceCode,
            [
                'student_number'  => $studentNumber,
                'experience_code' => $experienceCode,
                'updated_fields'  => $changedFields,
                'updated_data'    => $newData,
            ],
            'student'
        );

        return redirect()->to('/student/experiences')->with('success', 'Data pengalaman berhasil diperbarui.');
    }

    public function delete($experienceCode)
    {
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $experience = $this->experienceModel->where('experience_code', $experienceCode)->where('user_id', $userId)->first();
        if (!$experience) {
            $this->logActivity(
                'FAILED_DELETE_EXPERIENCE_NOT_FOUND',
                'Gagal menghapus pengalaman: Data tidak ditemukan atau akses ditolak (Code: ' . $experienceCode . ')',
                [
                    'student_number'  => $studentNumber,
                    'experience_code' => $experienceCode,
                ],
                'student'
            );

            return redirect()->to('/student/experiences')->with('error', 'Data tidak ditemukan atau akses ditolak.');
        }

        $this->experienceModel->where('experience_code', $experienceCode)->delete();
        $this->logActivity(
            'DELETE_EXPERIENCE_SUCCESS',
            'Mahasiswa menghapus pengalaman: ' . $experience->title,
            [
                'student_number'  => $studentNumber,
                'experience_code' => $experienceCode,
            ],
            'student'
        );

        return redirect()->to('/student/experiences')->with('success', 'Data pengalaman berhasil dihapus.');
    }
}
