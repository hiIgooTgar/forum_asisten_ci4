<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TakenCoursesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['taken_course_code' => '182718212_22010001', 'user_id' => 1, 'course_id' => 1, 'grade' => 'A', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['taken_course_code' => '182434343_22010001', 'user_id' => 1, 'course_id' => 2, 'grade' => 'A', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['taken_course_code' => '343437182_22010002', 'user_id' => 2, 'course_id' => 1, 'grade' => 'A', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['taken_course_code' => '134343282_22010003', 'user_id' => 2, 'course_id' => 3, 'grade' => 'B+', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['taken_course_code' => '182342332_22010001', 'user_id' => 3, 'course_id' => 4, 'grade' => 'A', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('taken_courses')->insertBatch($data);
    }
}
