<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StudyProgramsSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            [
                'program_main'        => 'prodi_if_01',
                'faculty_params'      => 'fiki_main_001',
                'program_code'        => 'IF',
                'program_name'        => 'S1 Informatika',
                'program_description' => 'Program Studi S1 Informatika',
                'degree_level'        => 'S1',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
            [
                'program_main'        => 'prodi_si_02',
                'faculty_params'      => 'fiki_main_001',
                'program_code'        => 'SI',
                'program_name'        => 'S1 Sistem Informasi',
                'program_description' => 'Program Studi S1 Sistem Informasi',
                'degree_level'        => 'S1',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
            [
                'program_main'        => 'prodi_ti_03',
                'faculty_params'      => 'fiki_main_001',
                'program_code'        => 'TI',
                'program_name'        => 'S1 Teknologi Informasi',
                'program_description' => 'Program Studi S1 Teknologi Informasi',
                'degree_level'        => 'S1',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
            [
                'program_main'        => 'prodi_ilkom_04',
                'faculty_params'      => 'fbs_main_002',
                'program_code'        => 'IK',
                'program_name'        => 'S1 Ilmu Komunikasi',
                'program_description' => 'Program Studi S1 Ilmu Komunikasi',
                'degree_level'        => 'S1',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
            [
                'program_main'        => 'prodi_mgt_05',
                'faculty_params'      => 'fbs_main_002',
                'program_code'        => 'MN',
                'program_name'        => 'S1 Manajemen',
                'program_description' => 'Program Studi S1 Manajemen',
                'degree_level'        => 'S1',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
        ];

        $this->db->table('study_programs')->insertBatch($data);
    }
}
