<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserExperiencesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['user_id' => 1, 'title' => 'Asisten Praktikum Pemrograman Web', 'organization_name' => 'Laboratorium Komputer AMIKOM', 'experience_type' => 'teaching_assistant', 'start_date' => '2023-09-01', 'end_date' => '2024-01-31', 'is_current' => 0, 'description' => 'Mengajar praktikum CI4 dan Laravel', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'title' => 'Ketua Divisi Pemrograman', 'organization_name' => 'HIMA Informatika', 'experience_type' => 'organizational', 'start_date' => '2023-01-10', 'end_date' => null, 'is_current' => 1, 'description' => 'Mengkoordinasi pelatihan coding mahasiswa', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['user_id' => 2, 'title' => 'Asisten Basis Data', 'organization_name' => 'Laboratorium Komputer AMIKOM', 'experience_type' => 'teaching_assistant', 'start_date' => '2023-09-01', 'end_date' => '2024-01-31', 'is_current' => 0, 'description' => 'Mengajar Praktikum MySQL & PostgreSQL', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['user_id' => 3, 'title' => 'Freelance Web Developer', 'organization_name' => 'PT Solusi Digital', 'experience_type' => 'work', 'start_date' => '2023-05-01', 'end_date' => null, 'is_current' => 1, 'description' => 'Mengembangkan aplikasi web klien', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['user_id' => 4, 'title' => 'Volunteer Relawan TIK', 'organization_name' => 'RTIK Banyumas', 'experience_type' => 'volunteering', 'start_date' => '2023-03-01', 'end_date' => '2023-12-31', 'is_current' => 0, 'description' => 'Edukasi literasi digital di desa', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('user_experiences')->insertBatch($data);
    }
}
