<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FacultiesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['faculty_code' => 'FIK', 'faculty_name' => 'Fakultas Ilmu Komputer', 'faculty_description' => 'Fakultas Teknologi dan Informasi', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['faculty_code' => 'FEB', 'faculty_name' => 'Fakultas Ekonomi dan Bisnis', 'faculty_description' => 'Fakultas Bisnis dan Manajemen', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['faculty_code' => 'FHS', 'faculty_name' => 'Fakultas Ilmu Sosial dan Humaniora', 'faculty_description' => 'Fakultas Komunikasi dan Humaniora', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['faculty_code' => 'FTS', 'faculty_name' => 'Fakultas Teknik dan Sains', 'faculty_description' => 'Fakultas Rekayasa Sains', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['faculty_code' => 'FDK', 'faculty_name' => 'Fakultas Desain dan Kreatif', 'faculty_description' => 'Fakultas Seni dan Media Kreatif', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('faculties')->insertBatch($data);
    }
}
