<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CoursesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['study_program_id' => 1, 'course_code' => 'IF101', 'course_name' => 'Pemrograman Web Lanjut', 'semester' => 4, 'credits' => 4, 'quota_needed' => 10, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['study_program_id' => 1, 'course_code' => 'IF102', 'course_name' => 'Sistem Basis Data', 'semester' => 3, 'credits' => 4, 'quota_needed' => 8, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['study_program_id' => 1, 'course_code' => 'IF103', 'course_name' => 'Pemrograman Berbasis Objek', 'semester' => 3, 'credits' => 4, 'quota_needed' => 12, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['study_program_id' => 2, 'course_code' => 'SI201', 'course_name' => 'Analisis Perancangan Sistem', 'semester' => 4, 'credits' => 3, 'quota_needed' => 6, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['study_program_id' => 3, 'course_code' => 'TI301', 'course_name' => 'Jaringan Komputer', 'semester' => 2, 'credits' => 4, 'quota_needed' => 5, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('courses')->insertBatch($data);
    }
}
