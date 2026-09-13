<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\AdministratorModel;

class AuthenticationAdmin extends BaseController
{
    protected $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdministratorModel();
        helper(['form', 'url']);
    }

    public function login()
    {
        $data = [
            'title' => 'Admin Login'
        ];
        return view('auth/login_admin', $data);
    }

    public function processLogin()
    {
        $rules = [
            'username_email' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Email atau Username wajib diisi.']
            ],
            'password' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Password wajib diisi.']
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $loginInput = trim($this->request->getPost('username_email'));
        $password   = (string) $this->request->getPost('password');

        $admin = $this->adminModel->getByUsernameOrEmail($loginInput);

        if ($admin && password_verify($password, $admin->password)) {
            if ((int)$admin->is_active !== 1) {
                return redirect()->back()->withInput()->with('error', 'Akun administrator Anda sedang nonaktif.');
            }

            session()->set([
                'admin_id'           => $admin->id,
                'admin_username'     => $admin->username,
                'admin_name'         => $admin->full_name,
                'admin_email'        => $admin->email,
                'admin_role'         => $admin->role,
                'admin_profile'      => $admin->profile,
                'is_admin_logged_in' => true
            ]);

            return redirect()->to(base_url('admin/dashboard'))->with('success', 'Selamat datang kembali, ' . $admin->full_name);
        }

        return redirect()->back()->withInput()->with('error', 'Username/Email atau password salah.');
    }

    public function logout()
    {
        session()->remove([
            'admin_id',
            'admin_username',
            'admin_name',
            'admin_email',
            'admin_role',
            'admin_profile',
            'is_admin_logged_in'
        ]);

        return redirect()->to(base_url('auth/login-admin'))->with('info', 'Anda telah berhasil keluar dari sistem.');
    }
}
