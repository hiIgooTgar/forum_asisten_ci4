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
        'course_main',
        'study_program_params',
        'course_code',
        'course_name',
        'semester',
        'credits',
        'quota_needed',
        'is_active',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'id'           => 'integer',
        'semester'     => 'integer',
        'credits'      => 'integer',
        'quota_needed' => 'integer',
        'is_active'    => 'integer',
    ];
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

    public function getCoursesByFacultyParam(string $facultyMain)
    {
        return $this->select('courses.*, study_programs.program_name, faculties.faculty_main, faculties.faculty_name')
            ->select('(SELECT COUNT(*) FROM taken_courses WHERE taken_courses.course_params = courses.course_main) as total_taken')
            ->join('study_programs', 'study_programs.program_main = courses.study_program_params', 'inner')
            ->join('faculties', 'faculties.faculty_main = study_programs.faculty_params', 'inner')
            ->where('faculties.faculty_main', $facultyMain)
            ->where('courses.is_active', 1)
            ->orderBy('study_programs.program_name', 'ASC')
            ->orderBy('courses.semester', 'ASC')
            ->findAll();
    }

    public function getAvailableCoursesByProgramParam(string $programMain)
    {
        return $this->select('courses.*, study_programs.program_name, study_programs.program_main')
            ->select('(SELECT COUNT(*) FROM taken_courses WHERE taken_courses.course_params = courses.course_main) as total_taken')
            ->join('study_programs', 'study_programs.program_main = courses.study_program_params', 'inner')
            ->where('study_programs.program_main', $programMain)
            ->where('courses.is_active', 1)
            ->orderBy('courses.semester', 'ASC')
            ->findAll();
    }

    public function getCourseByParam(string $courseMain)
    {
        return $this->select('courses.*, study_programs.program_name, faculties.faculty_name')
            ->select('(SELECT COUNT(*) FROM taken_courses WHERE taken_courses.course_params = courses.course_main) as total_taken')
            ->join('study_programs', 'study_programs.program_main = courses.study_program_params', 'left')
            ->join('faculties', 'faculties.faculty_main = study_programs.faculty_params', 'left')
            ->where('courses.course_main', $courseMain)
            ->first();
    }

    public function getActiveCoursesGroupedByFaculty()
    {
        return $this->select('
                courses.*, 
                study_programs.program_name, 
                study_programs.program_main, 
                faculties.faculty_name, 
                faculties.faculty_main
            ')
            ->select('(SELECT COUNT(*) FROM taken_courses WHERE taken_courses.course_params = courses.course_main) as total_taken')
            ->join('study_programs', 'study_programs.program_main = courses.study_program_params', 'inner')
            ->join('faculties', 'faculties.faculty_main = study_programs.faculty_params', 'inner')
            ->where('courses.is_active', 1)
            ->orderBy('faculties.faculty_name', 'ASC')
            ->orderBy('study_programs.program_name', 'ASC')
            ->orderBy('courses.semester', 'ASC')
            ->findAll();
    }
}
