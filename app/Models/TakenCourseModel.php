<?php

namespace App\Models;

use CodeIgniter\Model;

class TakenCourseModel extends Model
{
    protected $table            = 'taken_courses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'taken_course_main',
        'user_params',
        'course_params',
        'grade',
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

    public function getTakenCoursesByUser($registrationMain)
    {
        return $this->select('taken_courses.*, courses.course_name, courses.course_code, courses.semester, courses.credits, study_programs.program_name')
            ->join('courses', 'courses.course_main = taken_courses.course_params')
            ->join('study_programs', 'study_programs.program_main = courses.study_program_params', 'left')
            ->where('taken_courses.user_params', $registrationMain)
            ->findAll();
    }

    public function countUserTakenCourses(string $registrationMain): string
    {
        return $this->where('user_params', $registrationMain)->countAllResults();
    }
}
