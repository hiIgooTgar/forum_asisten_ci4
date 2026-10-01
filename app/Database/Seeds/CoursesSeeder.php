<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CoursesSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            [
                'course_main'          => 'crs_pbo_01',
                'study_program_params' => 'prodi_if_01',
                'course_code'          => 'IF101',
                'course_name'          => 'Pemrograman Berbasis Objek',
                'semester'             => 3,
                'credits'              => 4,
                'quota_needed'         => 6,
                'is_active'            => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
            [
                'course_main'          => 'crs_web_02',
                'study_program_params' => 'prodi_if_01',
                'course_code'          => 'IF102',
                'course_name'          => 'Pemrograman Web Lanjut',
                'semester'             => 4,
                'credits'              => 4,
                'quota_needed'         => 8,
                'is_active'            => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
            [
                'course_main'          => 'crs_basdat_03',
                'study_program_params' => 'prodi_si_02',
                'course_code'          => 'SI201',
                'course_name'          => 'Sistem Basis Data',
                'semester'             => 2,
                'credits'              => 3,
                'quota_needed'         => 5,
                'is_active'            => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
            [
                'course_main'          => 'crs_jaringan_04',
                'study_program_params' => 'prodi_if_01',
                'course_code'          => 'IF103',
                'course_name'          => 'Jaringan Komputer',
                'semester'             => 3,
                'credits'              => 3,
                'quota_needed'         => 4,
                'is_active'            => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
            [
                'course_main'          => 'crs_desainweb_05',
                'study_program_params' => 'prodi_ti_03',
                'course_code'          => 'BD301',
                'course_name'          => 'Desain Web UI/UX',
                'semester'             => 2,
                'credits'              => 3,
                'quota_needed'         => 3,
                'is_active'            => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
        ];

        $this->db->table('courses')->insertBatch($data);
    }
}
