<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TakenCoursesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['user_id' => 1, 'course_id' => 1, 'grade' => 'A', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'course_id' => 2, 'grade' => 'A', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['user_id' => 2, 'course_id' => 1, 'grade' => 'A', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['user_id' => 2, 'course_id' => 3, 'grade' => 'B+', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['user_id' => 3, 'course_id' => 4, 'grade' => 'A', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('taken_courses')->insertBatch($data);
    }
}
