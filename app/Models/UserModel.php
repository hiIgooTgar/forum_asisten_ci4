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
        'registration_main',
        'student_number',
        'full_name',
        'email',
        'phone_number',
        'password',
        'faculty_params',
        'study_program_params',
        'class_params',
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
        'verification_status',
        'certificate_file',
        'membership_status',
        'status_account',
        'remember_token'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

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

    public function getStudentProfile(string $registrationMain)
    {
        return $this->db->table($this->table)
            ->select('users.*, 
                 faculties.faculty_name, 
                 study_programs.program_name, 
                 study_programs.degree_level,
                 class_groups.class_name')
            ->join('faculties', 'faculties.faculty_main = users.faculty_params', 'left')
            ->join('study_programs', 'study_programs.program_main = users.study_program_params', 'left')
            ->join('class_groups', 'class_groups.class_main = users.class_params', 'left')
            ->where('users.registration_main', $registrationMain)
            ->get()
            ->getRow();
    }
}
