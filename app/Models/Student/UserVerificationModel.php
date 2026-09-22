<?php

namespace App\Models\Student;

use CodeIgniter\Model;

class UserVerificationModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['verification_status'];

    public function getStudentVerificationData(int $userId)
    {
        return $this->db->table('users u')
            ->select('
                u.*, 
                f.faculty_code, f.faculty_name,
                sp.program_code, sp.program_name, sp.degree_level,
                cg.class_name, cg.academic_year,
                ud.document_code, ud.student_card_file, ud.application_letter_file, 
                ud.cv_file, ud.latest_transcript_file, ud.statement_letter_file, 
                ud.registration_form_file, ud.created_at as document_created_at
            ')
            ->join('faculties f', 'f.id = u.faculty_id', 'left')
            ->join('study_programs sp', 'sp.id = u.study_program_id', 'left')
            ->join('class_groups cg', 'cg.id = u.class_id', 'left')
            ->join('user_documents ud', 'ud.user_id = u.id', 'left')
            ->where('u.id', $userId)
            ->get()
            ->getRow();
    }

    public function getTakenCoursesByUserId(int $userId): array
    {
        return $this->db->table('taken_courses tc')
            ->select('tc.id as taken_id, tc.taken_course_code, tc.grade, c.course_code, c.course_name, c.semester, c.credits')
            ->join('courses c', 'c.id = tc.course_id')
            ->where('tc.user_id', $userId)
            ->get()
            ->getResult();
    }

    public function completeVerification(int $userId): bool
    {
        return $this->update($userId, [
            'verification_status' => 'completed'
        ]);
    }
}
