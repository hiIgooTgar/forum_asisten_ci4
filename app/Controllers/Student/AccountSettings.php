<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CompanyApplicationModel;

class AccountSettings extends BaseController
{
    protected $userModel;
    protected $appProfileModel;

    public function __construct()
    {
        $this->userModel       = new UserModel();
        $this->appProfileModel = new CompanyApplicationModel();
    }

    public function index()
    {
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $student = $this->userModel->find($userId);
        if (!$student) {
            return redirect()->to('/auth/login')->with('error', 'Data pengguna tidak ditemukan.');
        }

        $appProfile = $this->appProfileModel->getAppProfile();

        $this->logActivity(
            'VIEW_ACCOUNT_SETTINGS',
            'Mahasiswa membuka halaman Pengaturan Akun (Keamanan & Hapus Akun)',
            ['student_number' => $student->student_number],
            'student'
        );

        $appProfileModel = new \App\Models\CompanyApplicationModel();
        $appProfile = $appProfileModel->first();

        $data = [
            'title'      => 'Pengaturan Akun & Keamanan',
            'student'    => $student,
            'appProfile' => $appProfile,
        ];

        return view('student/account_settings/index', $data);
    }

    public function changePassword()
    {
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');
        $student       = $this->userModel->getStudentProfile($userId);

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        if (isset($student->verification_status) && $student->verification_status === 'completed') {
            return redirect()->back()->with('error', 'Akses ditolak: Pendaftaran Anda telah terverifikasi dan data telah terkunci.');
        }

        $validationRules = [
            'current_password' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Password saat ini wajib diisi.',
                ],
            ],
            'new_password' => [
                'rules'  => 'required|min_length[8]|max_length[255]|regex_match[/[A-Z]/]|regex_match[/[0-9]/]|regex_match[/[\W_]/]',
                'errors' => [
                    'required'    => 'Password baru wajib diisi.',
                    'min_length'  => 'Password baru minimal harus 8 karakter.',
                    'max_length'  => 'Password baru maksimal 255 karakter.',
                    'regex_match' => 'Password baru harus mengandung huruf kapital, angka, dan karakter simbol.',
                ],
            ],
            'confirm_new_password' => [
                'rules'  => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'Konfirmasi Password baru wajib diisi.',
                    'matches'  => 'Konfirmasi Password baru tidak cocok dengan Password baru.',
                ],
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()
                ->with('error', 'Gagal memperbarui kata sandi. Silakan periksa kembali form Anda.')
                ->with('errors', $this->validator->getErrors());
        }

        $currentPassword = (string)$this->request->getPost('current_password');
        $newPassword     = (string)$this->request->getPost('new_password');

        if (!password_verify($currentPassword, $student->password)) {
            return redirect()->back()->withInput()->with('errors', [
                'current_password' => 'Kata sandi saat ini yang Anda masukkan salah.'
            ])->with('error', 'Kata sandi saat ini yang Anda masukkan salah.');
        }

        if (password_verify($newPassword, $student->password)) {
            return redirect()->back()->withInput()->with('errors', [
                'new_password' => 'Kata sandi baru tidak boleh sama dengan kata sandi saat ini.'
            ])->with('error', 'Kata sandi baru tidak boleh sama dengan kata sandi saat ini.');
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->userModel->update($userId, [
            'password'   => $hashedPassword,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->logActivity(
            'CHANGE_PASSWORD_SUCCESS',
            'Mahasiswa berhasil mengubah kata sandi akun',
            ['student_number' => $studentNumber],
            'student'
        );

        return redirect()->to('/student/account-settings')->with('success', 'Kata sandi Anda berhasil diperbarui.');
    }

    public function deleteAccount()
    {
        $userId        = session()->get('user_id');
        $studentNumber = session()->get('student_number');

        if (!$userId || !$studentNumber) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $student = $this->userModel->find($userId);
        if (!$student) {
            return redirect()->to('/auth/login')->with('error', 'Data pengguna tidak ditemukan.');
        }

        if (isset($student->verification_status) && $student->verification_status === 'completed') {
            return redirect()->back()->with('error', 'Akses ditolak: Akun tidak dapat dihapus karena pendaftaran Anda telah terverifikasi secara resmi.');
        }

        $validationRules = [
            'delete_confirm_password' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Kata sandi konfirmasi wajib diisi untuk menghapus akun.',
                ],
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()
                ->with('error', 'Gagal menghapus akun. Silakan periksa kembali kata sandi Anda.')
                ->with('errors', $this->validator->getErrors());
        }

        $passwordConfirm = (string)$this->request->getPost('delete_confirm_password');

        if (!password_verify($passwordConfirm, $student->password)) {
            return redirect()->back()->withInput()->with('errors', [
                'delete_confirm_password' => 'Kata sandi konfirmasi tidak sesuai. Penghapusan akun dibatalkan.'
            ])->with('error', 'Kata sandi konfirmasi tidak sesuai. Penghapusan akun dibatalkan.');
        }

        if (!empty($student->profile) && $student->profile !== 'profile-default.png') {
            $basePath = FCPATH . 'uploads/profile_student/';
            if (file_exists($basePath . $student->profile)) {
                @unlink($basePath . $student->profile);
            }
            if (file_exists($basePath . 'real/' . $student->profile)) {
                @unlink($basePath . 'real/' . $student->profile);
            }
        }

        $this->logActivity(
            'DELETE_ACCOUNT_SUCCESS',
            'Mahasiswa secara mandiri menghapus akun miliknya secara permanen',
            [
                'student_number' => $studentNumber,
                'email'          => $student->email ?? null,
            ],
            'student'
        );

        $this->userModel->delete($userId);

        session()->remove([
            'user_id',
            'student_number',
            'full_name',
            'email',
            'profile',
            'is_student_logged_in'
        ]);

        return redirect()->to('/auth/login')->with('success', 'Akun Anda berhasil dihapus secara permanen.');
    }
}
