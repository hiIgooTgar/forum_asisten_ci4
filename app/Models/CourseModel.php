<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table            = 'courses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'study_program_id',
        'course_code',
        'course_name',
        'semester',
        'credits',
        'quota_needed',
        'is_active',
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

    public function getCoursesByFaculty(int $facultyId)
    {
        return $this->select('courses.*, study_programs.program_name')
            ->join('study_programs', 'study_programs.id = courses.study_program_id')
            ->where('study_programs.faculty_id', $facultyId)
            ->orderBy('study_programs.program_name', 'ASC')
            ->orderBy('courses.semester', 'ASC')
            ->findAll();
    }

    public function getAvailableCoursesByProgram(int $studyProgramId)
    {
        return $this->select('courses.*, study_programs.program_name')
            ->join('study_programs', 'study_programs.id = courses.study_program_id')
            ->where('courses.study_program_id', $studyProgramId)
            ->orderBy('courses.semester', 'ASC')
            ->findAll();
    }
}
