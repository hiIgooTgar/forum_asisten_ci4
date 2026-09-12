<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'student_number',
        'full_name',
        'email',
        'phone_number',
        'password',
        'faculty_id',
        'study_program_id',
        'class_id',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'province',
        'regency',
        'subdistrict',
        'village',
        'address',
        'gpa',
        'profile',
        'otp_code',
        'otp_created_at',
        'otp_expires_at',
        'email_verified_at',
        'is_verified',
        'certificate_file',
        'status_account',
        'remember_token'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    public function saveResetToken(string $email, string $token): bool
    {
        $db = \Config\Database::connect();
        $db->table('password_resets')->where('email', $email)->delete();

        return $db->table('password_resets')->insert([
            'email'      => $email,
            'token'      => $token,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getResetToken(string $token)
    {
        $db = \Config\Database::connect();
        return $db->table('password_resets')->where('token', $token)->get()->getRow();
    }

    public function deleteResetToken(string $email): bool
    {
        $db = \Config\Database::connect();
        return $db->table('password_resets')->where('email', $email)->delete();
    }

    public function updatePasswordByEmail(string $email, string $hashedPassword): bool
    {
        return $this->where('email', $email)->set([
            'password'   => $hashedPassword,
            'updated_at' => date('Y-m-d H:i:s')
        ])->update();
    }
}
