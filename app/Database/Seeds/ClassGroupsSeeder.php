<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClassGroupsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['study_program_id' => 1, 'class_name' => 'IF-22-A', 'academic_year' => '2024/2025', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['study_program_id' => 1, 'class_name' => 'IF-22-B', 'academic_year' => '2024/2025', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['study_program_id' => 2, 'class_name' => 'SI-22-A', 'academic_year' => '2024/2025', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['study_program_id' => 3, 'class_name' => 'TI-23-A', 'academic_year' => '2024/2025', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['study_program_id' => 4, 'class_name' => 'AK-22-A', 'academic_year' => '2024/2025', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('class_groups')->insertBatch($data);
    }
}
