<?php

namespace App\Models\Student;

use CodeIgniter\Model;

class StudentDashboardModel extends Model
{
    public function getStudentProfile(int $userId)
    {
        return $this->db->table('users')
            ->select('
                users.*, 
                study_programs.program_name, 
                study_programs.program_code,
                faculties.faculty_name,
                faculties.faculty_code
            ')
            ->join('study_programs', 'study_programs.id = users.study_program_id', 'left')
            ->join('faculties', 'faculties.id = users.faculty_id', 'left')
            ->where('users.id', $userId)
            ->get()
            ->getRow();
    }

    public function getActiveEvent()
    {
        return $this->db->table('system_event_settings')
            ->where('is_active', 1)
            ->orderBy('id', 'DESC')
            ->get()
            ->getRow();
    }

    public function getDocumentStatus(int $userId)
    {
        return $this->db->table('user_documents')
            ->where('user_id', $userId)
            ->get()
            ->getRow();
    }
}
