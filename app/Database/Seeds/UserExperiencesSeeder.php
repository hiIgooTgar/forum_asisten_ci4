<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserExperiencesSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            [
                'experience_main'   => 'exp_code_001',
                'user_params'       => 'user_reg_001',
                'title'             => 'Anggota Divisi Keorganisasian HMTI',
                'organization_name' => 'Himpunan Mahasiswa Teknik Informatika',
                'experience_type'   => 'organizational',
                'year_occurred'     => '2023',
                'is_current'        => 1,
                'description'       => 'Mengelola kegiatan keorganisasian mahasiswa informatika.',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'experience_main'   => 'exp_code_002',
                'user_params'       => 'user_reg_001',
                'title'             => 'Juara 2 Web Design Competition',
                'organization_name' => 'BEM Universitas Amikom Purwokerto',
                'experience_type'   => 'competition',
                'year_occurred'     => '2024',
                'is_current'        => 0,
                'description'       => 'Membuat landing page interaktif berbasis Vue.js dan CI4.',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'experience_main'   => 'exp_code_003',
                'user_params'       => 'user_reg_002',
                'title'             => 'Asisten Lab Komputer Dasar',
                'organization_name' => 'Lab Komputer Amikom Purwokerto',
                'experience_type'   => 'teaching_assistant',
                'year_occurred'     => '2024',
                'is_current'        => 0,
                'description'       => 'Mendampingi mahasiswa praktikum Algoritma Pemrograman.',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'experience_main'   => 'exp_code_004',
                'user_params'       => 'user_reg_003',
                'title'             => 'Junior Web Developer Freelance',
                'organization_name' => 'PT Purwokerto Digital Creative',
                'experience_type'   => 'work',
                'year_occurred'     => '2023',
                'is_current'        => 1,
                'description'       => 'Mengembangkan RESTful API dengan CodeIgniter 4.',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'experience_main'   => 'exp_code_005',
                'user_params'       => 'user_reg_004',
                'title'             => 'Sertifikasi BNSP Junior Graphic Designer',
                'organization_name' => 'BNSP Indonesia',
                'experience_type'   => 'certification',
                'year_occurred'     => '2024',
                'is_current'        => 0,
                'description'       => 'Lulus sertifikasi profesi kompetensi desain grafis.',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
        ];

        $this->db->table('user_experiences')->insertBatch($data);
    }
}
