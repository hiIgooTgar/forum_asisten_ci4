<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FacultiesSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $randomCode = 'faculties_' . strtoupper(bin2hex(random_bytes(32)));

        $data = [
            ['faculty_params' => $randomCode, 'faculty_code' => 'FIK', 'faculty_name' => 'Fakultas Ilmu Komputer', 'faculty_description' => 'Fakultas Teknologi dan Informasi', 'created_at' => $now, 'updated_at' => $now],
            ['faculty_params' => $randomCode, 'faculty_code' => 'FEB', 'faculty_name' => 'Fakultas Ekonomi dan Bisnis', 'faculty_description' => 'Fakultas Bisnis dan Manajemen', 'created_at' => $now, 'updated_at' => $now],
            ['faculty_params' => $randomCode, 'faculty_code' => 'FHS', 'faculty_name' => 'Fakultas Ilmu Sosial dan Humaniora', 'faculty_description' => 'Fakultas Komunikasi dan Humaniora', 'created_at' => $now, 'updated_at' => $now],
            ['faculty_params' => $randomCode, 'faculty_code' => 'FTS', 'faculty_name' => 'Fakultas Teknik dan Sains', 'faculty_description' => 'Fakultas Rekayasa Sains', 'created_at' => $now, 'updated_at' => $now],
            ['faculty_params' => $randomCode, 'faculty_code' => 'FDK', 'faculty_name' => 'Fakultas Desain dan Kreatif', 'faculty_description' => 'Fakultas Seni dan Media Kreatif', 'created_at' => $now, 'updated_at' => $now],
        ];

        $this->db->table('faculties')->insertBatch($data);
    }
}
