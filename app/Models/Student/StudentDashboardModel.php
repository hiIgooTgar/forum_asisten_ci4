<?php

namespace App\Models\Student;

use CodeIgniter\Model;

class StudentDashboardModel extends Model
{
    public function getStudentProfile($identifier)
    {
        $builder = $this->db->table('users')
            ->select('
                users.*, 
                study_programs.program_name, 
                study_programs.program_code,
                faculties.faculty_name,
                faculties.faculty_code
            ')
            ->join('study_programs', 'study_programs.program_main = users.study_program_params', 'left')
            ->join('faculties', 'faculties.faculty_main = users.faculty_params', 'left');

        $builder->where('users.registration_main', $identifier);
        return $builder->get()->getRow();
    }

    public function getActiveEvent()
    {
        return $this->db->table('system_event_settings')
            ->where('is_active', 1)
            ->orderBy('id', 'DESC')
            ->get()
            ->getRow();
    }

    public function getDocumentStatus(string $identifier)
    {
        return $this->db->table('user_documents')
            ->where('user_params', $identifier)
            ->get()
            ->getRow();
    }
}
