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
        'taken_course_code',
        'user_id',
        'course_id',
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

    public function getTakenCoursesByUser($userId)
    {
        return $this->select('taken_courses.*, courses.course_name, courses.course_code, courses.semester, courses.credits, study_programs.program_name')
            ->join('courses', 'courses.id = taken_courses.course_id')
            ->join('study_programs', 'study_programs.id = courses.study_program_id', 'left')
            ->where('taken_courses.user_id', $userId)
            ->findAll();
    }

    public function countUserTakenCourses(int $userId): int
    {
        return $this->where('user_id', $userId)->countAllResults();
    }
}
