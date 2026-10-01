<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AccountSettings extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $registrationMain = session()->get('registration_main');
        $studentNumber    = session()->get('student_number');
        $fullName         = session()->get('full_name');
        $email            = session()->get('email');
        $isStudentLogged  = session()->get('is_student_logged_in');

        if (!$registrationMain || !$studentNumber || !$fullName || !$email || !$isStudentLogged) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir atau tidak valid.');
        }

        $student = $this->userModel->getStudentProfile($registrationMain);
        if (!$student) {
            return redirect()->to('/auth/login')->with('error', 'Data pengguna tidak ditemukan.');
        }

        $data = [
            'title'   => 'Pengaturan Akun & Keamanan',
            'student' => $student,
        ];

        return view('student/account_settings/index', $data);
    }

    public function changePassword()
    {
        $registrationMain = session()->get('registration_main');
        $studentNumber    = session()->get('student_number');
        $fullName         = session()->get('full_name');
        $email            = session()->get('email');
        $isStudentLogged  = session()->get('is_student_logged_in');

        if (!$registrationMain || !$studentNumber || !$fullName || !$email || !$isStudentLogged) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir atau tidak valid.');
        }

        $student = $this->userModel->getStudentProfile($registrationMain);
        if (!$student) {
            return redirect()->to('/auth/login')->with('error', 'Data pengguna tidak ditemukan.');
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
        $newPassword      = (string)$this->request->getPost('new_password');

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
        $this->userModel->update($student->id, [
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
        $registrationMain = session()->get('registration_main');
        $studentNumber    = session()->get('student_number');
        $fullName         = session()->get('full_name');
        $email            = session()->get('email');
        $isStudentLogged  = session()->get('is_student_logged_in');

        if (!$registrationMain || !$studentNumber || !$fullName || !$email || !$isStudentLogged) {
            return redirect()->to('/auth/login')->with('error', 'Sesi Anda telah berakhir atau tidak valid.');
        }

        $student = $this->userModel->getStudentProfile($registrationMain);
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
                'registration_main' => $registrationMain,
                'student_number'    => $studentNumber,
                'email'             => $student->email ?? null,
            ],
            'student'
        );

        $this->userModel->delete($student->id);

        session()->remove([
            'registration_main',
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
