<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserExperiencesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id'         => 1,
                'experience_code' => '1827182_22010001',
                'title'           => 'Asisten Praktikum Pemrograman Web',
                'organization_name' => 'Laboratorium Komputer AMIKOM',
                'experience_type' => 'teaching_assistant',
                'year_occurred'   => '2023',
                'is_current'      => 0,
                'description'     => 'Mengajar praktikum CI4 dan Laravel',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'         => 1,
                'experience_code' => '5938192_22010001',
                'title'           => 'Ketua Divisi Pemrograman',
                'organization_name' => 'HIMA Informatika',
                'experience_type' => 'organizational',
                'year_occurred'   => '2024',
                'is_current'      => 1,
                'description'     => 'Mengkoordinasi pelatihan coding mahasiswa',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'         => 2,
                'experience_code' => '8492018_22010002',
                'title'           => 'Asisten Basis Data',
                'organization_name' => 'Laboratorium Komputer AMIKOM',
                'experience_type' => 'teaching_assistant',
                'year_occurred'   => '2023',
                'is_current'      => 0,
                'description'     => 'Mengajar Praktikum MySQL & PostgreSQL',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'         => 3,
                'experience_code' => '3928104_22010003',
                'title'           => 'Freelance Web Developer',
                'organization_name' => 'PT Solusi Digital',
                'experience_type' => 'work',
                'year_occurred'   => '2025',
                'is_current'      => 1,
                'description'     => 'Mengembangkan aplikasi web klien',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'         => 4,
                'experience_code' => '7102938_22010004',
                'title'           => 'Volunteer Relawan TIK',
                'organization_name' => 'RTIK Banyumas',
                'experience_type' => 'volunteering',
                'year_occurred'   => '2026',
                'is_current'      => 0,
                'description'     => 'Edukasi literasi digital di desa',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('user_experiences')->insertBatch($data);
    }
}
