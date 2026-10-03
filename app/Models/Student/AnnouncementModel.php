<?php

namespace App\Models\Student;

use CodeIgniter\Model;
use App\Models\SystemEventSettingModel;

class AnnouncementModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    public function getStudentAnnouncement(string $registrationMain)
    {
        return $this->db->table('users')
            ->select('users.*, 
                      faculties.faculty_name, 
                      study_programs.program_name, 
                      study_programs.degree_level,
                      class_groups.class_name,
                      user_documents.student_card_file,
                      user_documents.application_letter_file,
                      user_documents.cv_file,
                      user_documents.latest_transcript_file,
                      user_documents.statement_letter_file,
                      user_documents.registration_form_file')
            ->join('faculties', 'faculties.faculty_main = users.faculty_params', 'left')
            ->join('study_programs', 'study_programs.program_main = users.study_program_params', 'left')
            ->join('class_groups', 'class_groups.class_main = users.class_params', 'left')
            ->join('user_documents', 'user_documents.user_params = users.registration_main', 'left')
            ->where('users.registration_main', $registrationMain)
            ->get()
            ->getRow();
    }

    public function getTakenCoursesWithResult(string $registrationMain)
    {
        return $this->db->table('taken_courses')
            ->select('taken_courses.*, courses.course_name, courses.course_code, courses.semester, courses.credits')
            ->join('courses', 'courses.course_main = taken_courses.course_params')
            ->where('taken_courses.user_params', $registrationMain)
            ->get()
            ->getResult();
    }

    public function getAnnouncementEvent()
    {
        $eventModel = new SystemEventSettingModel();
        return $eventModel->getEventByKey('announcement_recruitment_result');
    }
}
