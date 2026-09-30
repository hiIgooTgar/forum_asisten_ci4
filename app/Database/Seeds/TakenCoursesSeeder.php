<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TakenCoursesSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $generateCode = function () {
            return 'fa_taken_course_' . strtoupper(bin2hex(random_bytes(16)));
        };

        $data = [
            ['taken_course_code' => $generateCode(), 'user_id' => 1, 'course_id' => 1, 'grade' => 'A', 'created_at' => $now, 'updated_at' => $now],
            ['taken_course_code' => $generateCode(), 'user_id' => 1, 'course_id' => 2, 'grade' => 'A', 'created_at' => $now, 'updated_at' => $now],
            ['taken_course_code' => $generateCode(), 'user_id' => 2, 'course_id' => 1, 'grade' => 'A', 'created_at' => $now, 'updated_at' => $now],
            ['taken_course_code' => $generateCode(), 'user_id' => 2, 'course_id' => 3, 'grade' => 'B+', 'created_at' => $now, 'updated_at' => $now],
            ['taken_course_code' => $generateCode(), 'user_id' => 3, 'course_id' => 4, 'grade' => 'A', 'created_at' => $now, 'updated_at' => $now],
        ];

        $this->db->table('taken_courses')->insertBatch($data);
    }
}
