<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TakenCoursesSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            [
                'taken_course_main' => 'tk_crs_001',
                'user_params'       => 'user_reg_001',
                'course_params'     => 'crs_pbo_01',
                'grade'             => 'A',
                'status_selection'  => 'pending',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'taken_course_main' => 'tk_crs_002',
                'user_params'       => 'user_reg_001',
                'course_params'     => 'crs_web_02',
                'grade'             => 'A-',
                'status_selection'  => 'pending',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'taken_course_main' => 'tk_crs_003',
                'user_params'       => 'user_reg_002',
                'course_params'     => 'crs_pbo_01',
                'grade'             => 'A',
                'status_selection'  => 'pending',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'taken_course_main' => 'tk_crs_004',
                'user_params'       => 'user_reg_003',
                'course_params'     => 'crs_basdat_03',
                'grade'             => 'B+',
                'status_selection'  => 'pending',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'taken_course_main' => 'tk_crs_005',
                'user_params'       => 'user_reg_004',
                'course_params'     => 'crs_desainweb_05',
                'grade'             => 'A',
                'status_selection'  => 'pending',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
        ];

        $this->db->table('taken_courses')->insertBatch($data);
    }
}
