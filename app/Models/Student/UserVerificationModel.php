<?php

namespace App\Models\Student;

use CodeIgniter\Model;

class UserVerificationModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['verification_status'];

    public function getStudentVerificationData(string $registrationMain)
    {
        return $this->db->table('users u')
            ->select('
                u.*, 
                f.faculty_code, f.faculty_name,
                sp.program_code, sp.program_name, sp.degree_level,
                cg.class_name, cg.academic_year,
                ud.document_main, ud.student_card_file, ud.application_letter_file, 
                ud.cv_file, ud.latest_transcript_file, ud.statement_letter_file, 
                ud.registration_form_file, ud.created_at as document_created_at
            ')
            ->join('faculties f', 'f.faculty_main = u.faculty_params', 'left')
            ->join('study_programs sp', 'sp.program_main = u.study_program_params', 'left')
            ->join('class_groups cg', 'cg.class_main = u.class_params', 'left')
            ->join('user_documents ud', 'ud.user_params = u.registration_main', 'left')
            ->where('u.registration_main', $registrationMain)
            ->get()
            ->getRow();
    }

    public function getTakenCoursesByRegistrationMain(string $registrationMain): array
    {
        return $this->db->table('taken_courses tc')
            ->select('
                tc.id as taken_id, 
                tc.taken_course_main, 
                tc.grade, 
                c.course_code, 
                c.course_name, 
                c.semester, 
                c.credits
            ')
            ->join('courses c', 'c.course_main = tc.course_params', 'left')
            ->where('tc.user_params', $registrationMain)
            ->get()
            ->getResult();
    }

    public function completeVerification(string $registrationMain): bool
    {
        return $this->where('registration_main', $registrationMain)
            ->set(['verification_status' => 'completed'])
            ->update();
    }
}
