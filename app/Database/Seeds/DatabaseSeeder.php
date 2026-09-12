<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('AdministratorsSeeder');
        $this->call('FacultiesSeeder');
        $this->call('StudyProgramsSeeder');
        $this->call('ClassGroupsSeeder');
        $this->call('UsersSeeder');
        $this->call('UserExperiencesSeeder');
        $this->call('UserDocumentsSeeder');
        $this->call('CoursesSeeder');
        $this->call('TakenCoursesSeeder');
        $this->call('SystemEventSettingsSeeder');
    }
}
