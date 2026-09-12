<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthenticationStudent extends BaseController
{
    protected $userModel;
    protected $helpers = ['url', 'form', 'auth'];

    public function __construct()
    {
        $this->userModel = new UserModel();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function login()
    {
        $data = [
            'title' => 'Login'
        ];
        return view('auth/login', $data);
    }

    public function processLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required'
        ];

        $messages = [
            'email' => [
                'required'    => 'Email tidak boleh kosong.',
                'valid_email' => 'Email harus berupa alamat email yang valid.'
            ],
            'password' => [
                'required' => 'Password tidak boleh kosong.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $email    = $this->request->getPost('email', FILTER_SANITIZE_EMAIL);
        $password = $this->request->getPost('password');
        $user     = $this->userModel->getByEmail($email);

        if ($user && password_verify($password, $user->password)) {
            if ($user->status_account === 'inactive') {
                session()->setFlashdata('error', 'Akun Anda sedang dinonaktifkan. Silakan hubungi admin.');
                return redirect()->back()->withInput();
            }

            if (!$user->is_verified) {
                if ($this->sendOtp($user)) {
                    session()->set('pending_user_id', $user->id);
                    session()->setFlashdata('info', 'Akun belum terverifikasi. Kode OTP baru telah dikirim ke email.');
                    return redirect()->to('auth/verify-otp');
                }

                session()->setFlashdata('error', 'Gagal mengirim kode OTP ke email.');
                return redirect()->back()->withInput();
            }

            session()->set([
                'user_id'              => $user->id,
                'full_name'            => $user->full_name,
                'email'                => $user->email,
                'is_student_logged_in' => true
            ]);

            return redirect()->to('student/dashboard');
        }

        session()->setFlashdata('error', 'Email atau password salah.');
        return redirect()->back()->withInput();
    }

    public function register()
    {
        $data = [
            'title' => 'Registrasi Akun'
        ];
        return view('auth/register', $data);
    }

    public function processRegister()
    {
        $rules = [
            'student_number'        => 'required|is_unique[users.student_number]',
            'full_name'             => 'required|trim',
            'email'                 => 'required|valid_email|is_unique[users.email]',
            'password'              => 'required|min_length[8]|regex_match[/[A-Z]/]|regex_match[/[0-9]/]|regex_match[/[\W_]/]',
            'password_confirmation' => 'required|matches[password]'
        ];

        $messages = [
            'student_number' => [
                'required'  => 'NIM tidak boleh kosong.',
                'is_unique' => 'NIM sudah terdaftar di sistem.'
            ],
            'full_name' => [
                'required' => 'Nama Lengkap tidak boleh kosong.'
            ],
            'email' => [
                'required'    => 'Email tidak boleh kosong.',
                'valid_email' => 'Email harus berupa alamat email yang valid.',
                'is_unique'   => 'Email sudah terdaftar di sistem.'
            ],
            'password' => [
                'required'    => 'Password tidak boleh kosong.',
                'min_length'  => 'Password minimal harus 8 karakter.',
                'regex_match' => 'Password harus mengandung huruf kapital, angka, dan karakter simbol.'
            ],
            'password_confirmation' => [
                'required' => 'Konfirmasi Password tidak boleh kosong.',
                'matches'  => 'Konfirmasi Password tidak cocok dengan password.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $currentTime = date('Y-m-d H:i:s');
        $data = [
            'student_number' => $this->request->getPost('student_number'),
            'full_name'      => $this->request->getPost('full_name'),
            'email'          => $this->request->getPost('email'),
            'password'       => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'is_verified'    => 0,
            'status_account' => 'active',
            'created_at'     => $currentTime,
            'updated_at'     => $currentTime
        ];

        $userId = $this->userModel->insert($data);
        $user   = $this->userModel->find($userId);

        if ($this->sendOtp($user)) {
            session()->set('pending_user_id', $userId);
            session()->setFlashdata('success', 'Registrasi berhasil. Silakan cek OTP di email Anda.');
            return redirect()->to('auth/verify-otp');
        }

        session()->setFlashdata('error', 'Registrasi berhasil, namun gagal mengirimkan email OTP.');
        return redirect()->to('auth/login');
    }

    public function verifyOtp()
    {
        if (!session()->get('pending_user_id')) {
            return redirect()->to('auth/login');
        }

        $data = [
            'title' => 'Verifikasi OTP'
        ];

        return view('auth/verify_otp', $data);
    }

    public function processOtp()
    {
        $pendingId = session()->get('pending_user_id');

        if (!$pendingId) {
            session()->setFlashdata('error', 'Sesi verifikasi telah habis. Silakan login kembali.');
            return redirect()->to('auth/login');
        }

        $otpArray = $this->request->getPost('otp');
        $inputOtp = is_array($otpArray) ? implode('', $otpArray) : $otpArray;
        $inputOtp = trim((string) $inputOtp);

        $user = $this->userModel->find($pendingId);

        if (!$user) {
            session()->setFlashdata('error', 'Pengguna tidak ditemukan.');
            return redirect()->to('auth/login');
        }

        $dbOtp        = trim((string) $user->otp_code);
        $isValidOtp   = ($dbOtp !== '' && $dbOtp === $inputOtp);
        $isNotExpired = (strtotime($user->otp_expires_at) >= time());

        if ($isValidOtp && $isNotExpired) {
            $currentTime = date('Y-m-d H:i:s');

            $this->userModel->update($user->id, [
                'is_verified'       => 1,
                'email_verified_at' => $currentTime,
                'otp_code'          => null,
                'otp_created_at'    => null,
                'otp_expires_at'    => null,
                'updated_at'        => $currentTime
            ]);

            session()->remove('pending_user_id');
            session()->setFlashdata('success', 'Email berhasil diverifikasi! Silakan login.');
            return redirect()->to('auth/login');
        }

        if (!$isNotExpired) {
            session()->setFlashdata('error', 'Kode OTP telah kedaluwarsa. Silakan kirim ulang OTP.');
        } else {
            session()->setFlashdata('error', 'Kode OTP tidak sesuai.');
        }

        return redirect()->to('auth/verify-otp');
    }

    public function forgetPassword()
    {
        $data = [
            'title' => 'Lupa Password'
        ];
        return view('auth/forget_password', $data);
    }

    public function processForgetPassword()
    {
        $rules = ['email' => 'required|valid_email'];
        $messages = [
            'email' => [
                'required'    => 'Email tidak boleh kosong.',
                'valid_email' => 'Email harus berupa alamat email yang valid.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $email = $this->request->getPost('email');
        $user  = $this->userModel->getByEmail($email);

        if (!$user) {
            session()->setFlashdata('error', 'Alamat email tidak ditemukan.');
            return redirect()->back()->withInput();
        }

        $token = bin2hex(random_bytes(32));
        $this->userModel->saveResetToken($email, $token);
        $resetLink = base_url("auth/reset-password?token={$token}&email=" . urlencode($email));

        if ($this->sendResetPasswordEmail($user, $resetLink)) {
            session()->setFlashdata('success', 'Tautan reset password telah dikirim ke email Anda.');
        } else {
            session()->setFlashdata('error', 'Gagal mengirim email reset password.');
        }

        return redirect()->to('auth/forget-password');
    }

    public function resetPassword()
    {
        $token = $this->request->getGet('token');
        $email = $this->request->getGet('email');

        $resetData = $this->userModel->getResetToken($token);

        if (!$resetData || $resetData->email !== $email) {
            session()->setFlashdata('error', 'Tautan reset password tidak valid atau sudah kadaluwarsa.');
            return redirect()->to('auth/login');
        }

        $data = [
            'title' => 'Reset Password',
            'token' => $token,
            'email' => $email
        ];
        return view('auth/reset_password', $data);
    }

    public function processResetPassword()
    {
        $token = $this->request->getPost('token');
        $email = $this->request->getPost('email');

        $rules = [
            'email'                 => 'required|valid_email',
            'password'              => 'required|min_length[8]|regex_match[/[A-Z]/]|regex_match[/[0-9]/]|regex_match[/[\W_]/]',
            'password_confirmation' => 'required|matches[password]'
        ];

        $messages = [
            'email' => [
                'required'    => 'Email tidak boleh kosong.',
                'valid_email' => 'Email harus berupa alamat email yang valid.'
            ],
            'password' => [
                'required'    => 'Password tidak boleh kosong.',
                'min_length'  => 'Password minimal harus 8 karakter.',
                'regex_match' => 'Password harus mengandung huruf kapital, angka, dan karakter simbol.'
            ],
            'password_confirmation' => [
                'required' => 'Konfirmasi Password tidak boleh kosong.',
                'matches'  => 'Konfirmasi Password tidak cocok dengan password.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->to('auth/reset-password?token=' . $token . '&email=' . urlencode($email));
        }

        $resetData = $this->userModel->getResetToken($token);
        if (!$resetData || $resetData->email !== $email) {
            session()->setFlashdata('error', 'Sesi reset password tidak valid.');
            return redirect()->to('auth/login');
        }

        $password = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);
        $this->userModel->updatePasswordByEmail($email, $password);
        $this->userModel->deleteResetToken($email);

        session()->setFlashdata('success', 'Password berhasil diperbarui. Silakan login kembali.');
        return redirect()->to('auth/login');
    }

    public function resendVerification()
    {
        $data = [
            'title' => 'Kirim Ulang Verifikasi'
        ];
        return view('auth/resend_verification', $data);
    }

    public function processResendVerification()
    {
        $rules = ['email' => 'required|valid_email'];
        $messages = [
            'email' => [
                'required'    => 'Email tidak boleh kosong.',
                'valid_email' => 'Email harus berupa alamat email yang valid.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $email = $this->request->getPost('email');
        $user  = $this->userModel->getByEmail($email);

        if (!$user) {
            session()->setFlashdata('error', 'Alamat email tidak terdaftar.');
            return redirect()->back()->withInput();
        }

        if ($user->is_verified) {
            session()->setFlashdata('info', 'Akun Anda sudah terverifikasi. Silakan login.');
            return redirect()->to('auth/login');
        }

        if ($this->sendOtp($user)) {
            session()->set('pending_user_id', $user->id);
            session()->setFlashdata('success', 'Kode OTP verifikasi baru berhasil dikirim ke email Anda.');
            return redirect()->to('auth/verify-otp');
        }

        session()->setFlashdata('error', 'Gagal mengirimkan kode OTP baru.');
        return redirect()->back()->withInput();
    }

    public function resendOtpAction()
    {
        $pendingId = session()->get('pending_user_id');

        if (!$pendingId) {
            return redirect()->to('auth/login');
        }

        $lastSent = session()->get('otp_last_sent');
        $cooldown = 180;

        if ($lastSent && (time() - $lastSent) < $cooldown) {
            $remaining = $cooldown - (time() - $lastSent);
            session()->setFlashdata('error', "Harap tunggu {$remaining} detik lagi untuk mengirim ulang OTP.");
            return redirect()->to('auth/verify-otp');
        }

        $user = $this->userModel->find($pendingId);

        if ($user && $this->sendOtp($user)) {
            session()->set('otp_last_sent', time());
            session()->setFlashdata('success', 'Kode OTP baru telah berhasil dikirim ke email Anda.');
        } else {
            session()->setFlashdata('error', 'Gagal mengirimkan kode OTP baru.');
        }

        return redirect()->to('auth/verify-otp');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('auth/login');
    }

    private function sendOtp($user): bool
    {
        $otp       = sprintf("%06d", mt_rand(0, 999999));
        $createdAt = date('Y-m-d H:i:s');
        $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        $this->userModel->update($user->id, [
            'otp_code'       => (string) $otp,
            'otp_created_at' => $createdAt,
            'otp_expires_at' => $expiresAt,
            'updated_at'     => $createdAt
        ]);

        session()->set('otp_last_sent', time());

        $config = [
            'protocol'   => 'smtp',
            'SMTPHost'   => env('email.SMTPHost', 'smtp.gmail.com'),
            'SMTPUser'   => env('email.SMTPUser'),
            'SMTPPass'   => env('email.SMTPPass'),
            'SMTPPort'   => (int) env('email.SMTPPort', 465),
            'SMTPCrypto' => env('email.SMTPCrypto', 'ssl'),
            'mailType'   => 'html',
            'charset'    => 'utf-8',
            'newline'    => "\r\n",
            'CRLF'       => "\r\n"
        ];

        $emailService = \Config\Services::email();
        $emailService->initialize($config);

        $emailService->setFrom(env('email.fromEmail'), env('email.fromName'));
        $emailService->setTo($user->email);
        $emailService->setSubject('Kode Verifikasi OTP - Forum Asisten AMIKOM');
        $emailService->setMessage($this->getOtpTemplate($user->full_name, $otp));

        if (!$emailService->send()) {
            log_message('error', 'Gagal Kirim OTP: ' . $emailService->printDebugger(['headers']));
            return false;
        }

        return true;
    }

    private function sendResetPasswordEmail($user, string $resetLink): bool
    {
        $config = [
            'protocol'   => 'smtp',
            'SMTPHost'   => env('email.SMTPHost', 'smtp.gmail.com'),
            'SMTPUser'   => env('email.SMTPUser'),
            'SMTPPass'   => env('email.SMTPPass'),
            'SMTPPort'   => (int) env('email.SMTPPort', 465),
            'SMTPCrypto' => env('email.SMTPCrypto', 'ssl'),
            'mailType'   => 'html',
            'charset'    => 'utf-8',
            'newline'    => "\r\n",
            'CRLF'       => "\r\n"
        ];

        $emailService = \Config\Services::email();
        $emailService->initialize($config);

        $emailService->setFrom(env('email.fromEmail'), env('email.fromName'));
        $emailService->setTo($user->email);
        $emailService->setSubject('Instruksi Reset Password - Forum Asisten AMIKOM');
        $emailService->setMessage($this->getResetPasswordTemplate($user->full_name, $resetLink));

        if (!$emailService->send()) {
            log_message('error', 'Gagal Kirim Reset Password: ' . $emailService->printDebugger(['headers']));
            return false;
        }

        return true;
    }
    private function getOtpTemplate(string $fullName, string $otp): string
    {
        return "
    <!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Kode OTP Verifikasi</title>
    </head>
    <body style='margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif;'>
        <table border='0' cellpadding='0' cellspacing='0' width='100%' style='padding: 40px 15px;'>
            <tr>
                <td align='center'>
                    <table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 500px; background-color: #ffffff; border-radius: 6px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;'>
                        <tr>
                            <td align='center' style='background: linear-gradient(135deg, #0a2481 0%, #0c1d61 60%, #081750 100%); padding: 36px 24px;'>
                                <div style='display: inline-block; background-color: rgba(255, 255, 255, 0.1); padding: 12px; margin-bottom: 12px;'>
                                    <svg width='28' height='28' viewBox='0 0 24 24' fill='none' stroke='#ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><rect x='3' y='11' width='18' height='11' rx='2' ry='2'></rect><path d='M7 11V7a5 5 0 0 1 10 0v4'></path></svg>
                                </div>
                                <h1 style='color: #ffffff; margin: 0; font-size: 20px; font-weight: 800; letter-spacing: -0.3px;'>Forum Asisten</h1>
                                <p style='color: #d8b4fe; margin: 4px 0 0 0; font-size: 12px; font-weight: 500;'>Universitas Amikom Purwokerto</p>
                            </td>
                        </tr>
                        <tr>
                            <td style='padding: 36px 32px 28px 32px;'>
                                <h2 style='color: #0f172a; margin: 0 0 10px 0; font-size: 18px; font-weight: 700; text-align: left;'>Verifikasi Kode OTP</h2>
                                <p style='color: #475569; margin: 0 0 24px 0; font-size: 14px; line-height: 1.6; text-align: left;'>
                                    Halo <b style='color: #0a2481;'>" . esc($fullName) . "</b>,<br>
                                    Gunakan kode verifikasi di bawah ini untuk melanjutkan akses akun Anda:
                                </p>
                                <div style='text-align: center; margin: 24px 0 28px 0;'>
                                    <div style='display: inline-block; background: rgba(10, 36, 129, 0.2); border: 2px dashed #0a2481; padding: 18px 36px; border-radius: 4px;'>
                                        <span style='font-family: \"Courier New\", Courier, monospace; font-size: 38px; font-weight: 800; letter-spacing: 10px; color: #0a2481;'>{$otp}</span>
                                    </div>
                                </div>
                                <div style='background-color: #fffbeb; border: 1px solid #fef3c7; padding: 12px 16px; margin-bottom: 24px; border-radius: 4px;'>
                                    <p style='color: #b45309; margin: 0; font-size: 12px; line-height: 1.5; text-align: left;'>
                                        Kode ini berlaku selama <b>10 menit</b>. Jangan bagikan kode verifikasi ini kepada siapa pun.
                                    </p>
                                </div>
                                <p style='color: #717171; margin: 0; font-size: 12px; line-height: 1.5; text-align: center;'>
                                    Jika Anda tidak melakukan permintaan ini, abaikan pesan email ini.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style='background-color: #f8fafc; border-top: 1px solid #f1f5f9; padding: 20px 32px; text-align: center;'>
                                <p style='color: #0a2481; margin: 0; font-size: 11px;'>
                                    &copy; " . date('Y') . " Forum Asisten Universitas Amikom Purwokerto. All rights reserved.
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>";
    }

    private function getResetPasswordTemplate(string $fullName, string $resetLink): string
    {
        return "
    <!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Reset Password Akun</title>
    </head>
    <body style='margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif;'>
        <table border='0' cellpadding='0' cellspacing='0' width='100%' style='padding: 40px 15px;'>
            <tr>
                <td align='center'>
                    <table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 500px; background-color: #ffffff; border-radius: 6px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;'>
                        <tr>
                            <td align='center' style='background: linear-gradient(135deg, #0a2481 0%, #0c1d61 60%, #081750 100%); padding: 36px 24px;'>
                                <div style='display: inline-block; background-color: rgba(255, 255, 255, 0.1); padding: 12px; margin-bottom: 12px;'>
                                    <svg width='28' height='28' viewBox='0 0 24 24' fill='none' stroke='#ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4'></path></svg>
                                </div>
                                <h1 style='color: #ffffff; margin: 0; font-size: 20px; font-weight: 800; letter-spacing: -0.3px;'>Forum Asisten</h1>
                                <p style='color: #d8b4fe; margin: 4px 0 0 0; font-size: 12px; font-weight: 500;'>Universitas Amikom Purwokerto</p>
                            </td>
                        </tr>
                        <tr>
                            <td style='padding: 36px 32px 28px 32px;'>
                                <h2 style='color: #0f172a; margin: 0 0 10px 0; font-size: 18px; font-weight: 700; text-align: left;'>Atur Ulang Password</h2>
                                <p style='color: #475569; margin: 0 0 24px 0; font-size: 14px; line-height: 1.6; text-align: left;'>
                                    Halo <b style='color: #0a2481;'>" . esc($fullName) . "</b>,<br>
                                    Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda. Silakan klik tombol di bawah ini:
                                </p>
                                <div style='text-align: center; margin: 30px 0;'>
                                    <a href='{$resetLink}' target='_blank' style='display: inline-block; background: linear-gradient(135deg, #0037ff 0%, #081750 100%); color: #ffffff; padding: 14px 32px; border-radius: 4px; text-decoration: none; font-weight: 700; font-size: 14px; box-shadow: 0 4px 12px rgba(10, 36, 129, 0.35); transition: all 0.2s;'>Reset Password Saya</a>
                                </div>
                                <div style='background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; border-radius: 4px; margin-bottom: 24px; word-break: break-all;'>
                                    <p style='color: #64748b; margin: 0 0 4px 0; font-size: 11px; font-weight: 600;'>Atau salin tautan berikut ke browser:</p>
                                    <a href='{$resetLink}' style='color: #0a2481; font-size: 11px; text-decoration: underline;'>{$resetLink}</a>
                                </div>
                                <p style='color: #717171; margin: 0; font-size: 12px; line-height: 1.5; text-align: center;'>
                                    Jika Anda tidak mengajukan pemulihan password, abaikan pesan ini dan password Anda akan tetap aman.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style='background-color: #f8fafc; border-top: 1px solid #f1f5f9; padding: 20px 32px; text-align: center;'>
                                <p style='color: #0a2481; margin: 0; font-size: 11px;'>
                                    &copy; " . date('Y') . " Forum Asisten Universitas Amikom Purwokerto. All rights reserved.
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>";
    }
}
