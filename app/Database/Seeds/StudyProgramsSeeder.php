<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StudyProgramsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['faculty_id' => 1, 'program_code' => 'IF', 'program_name' => 'Informatika', 'program_description' => 'Prodi Teknik Informatika', 'degree_level' => 'S1', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['faculty_id' => 1, 'program_code' => 'SI', 'program_name' => 'Sistem Informasi', 'program_description' => 'Prodi Sistem Informasi', 'degree_level' => 'S1', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['faculty_id' => 1, 'program_code' => 'TI', 'program_name' => 'Teknologi Informasi', 'program_description' => 'Prodi Teknologi Informasi', 'degree_level' => 'D3', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['faculty_id' => 2, 'program_code' => 'AK', 'program_name' => 'Akuntansi', 'program_description' => 'Prodi Akuntansi Keuangan', 'degree_level' => 'S1', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['faculty_id' => 3, 'program_code' => 'IK', 'program_name' => 'Ilmu Komunikasi', 'program_description' => 'Prodi Media & Public Relation', 'degree_level' => 'S1', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('study_programs')->insertBatch($data);
    }
}
