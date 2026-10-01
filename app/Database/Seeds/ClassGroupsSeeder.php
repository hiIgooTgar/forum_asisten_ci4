<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClassGroupsSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            [
                'class_main'           => 'class_if22a_01',
                'study_program_params' => 'prodi_if_01',
                'class_name'           => 'IF 22 A',
                'academic_year'        => '2022/2023',
                'is_active'            => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
            [
                'class_main'           => 'class_if22b_02',
                'study_program_params' => 'prodi_if_01',
                'class_name'           => 'IF 22 B',
                'academic_year'        => '2022/2023',
                'is_active'            => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
            [
                'class_main'           => 'class_si22a_03',
                'study_program_params' => 'prodi_si_02',
                'class_name'           => 'SI 22 A',
                'academic_year'        => '2022/2023',
                'is_active'            => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
            [
                'class_main'           => 'class_bd23a_04',
                'study_program_params' => 'prodi_ti_03',
                'class_name'           => 'BD 23 A',
                'academic_year'        => '2023/2024',
                'is_active'            => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
            [
                'class_main'           => 'class_ik22a_05',
                'study_program_params' => 'prodi_ilkom_04',
                'class_name'           => 'IK 22 A',
                'academic_year'        => '2022/2023',
                'is_active'            => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
        ];

        $this->db->table('class_groups')->insertBatch($data);
    }
}
