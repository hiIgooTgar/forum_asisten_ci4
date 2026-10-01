<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FacultiesSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            [
                'faculty_main'        => 'fiki_main_001',
                'faculty_code'        => 'FIKI',
                'faculty_name'        => 'Fakultas Ilmu Komputer',
                'faculty_description' => 'Fakultas yang berfokus pada teknologi, jaringan, dan pengembangan perangkat lunak.',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
            [
                'faculty_main'        => 'fbs_main_002',
                'faculty_code'        => 'FBS',
                'faculty_name'        => 'Fakultas Bisnis dan Sosial',
                'faculty_description' => 'Fakultas bidang manajemen, ilmu komunikasi, dan akuntansi.',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
            [
                'faculty_main'        => 'fkes_main_003',
                'faculty_code'        => 'FIKES',
                'faculty_name'        => 'Fakultas Ilmu Kesehatan',
                'faculty_description' => 'Fakultas bidang teknologi laboratorium medik dan kesehatan.',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
            [
                'faculty_main'        => 'fse_main_004',
                'faculty_code'        => 'FSE',
                'faculty_name'        => 'Fakultas Sains dan Rekayasa',
                'faculty_description' => 'Fakultas rekayasa sistem terapan dan sains data.',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
            [
                'faculty_main'        => 'pasca_main_005',
                'faculty_code'        => 'PASCA',
                'faculty_name'        => 'Program Pascasarjana',
                'faculty_description' => 'Program pendidikan Magister Magister Komputer.',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
        ];

        $this->db->table('faculties')->insertBatch($data);
    }
}
