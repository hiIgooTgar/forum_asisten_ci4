<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTakenCoursesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'taken_course_main' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
            ],
            'user_params' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'course_params' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'grade' => [
                'type'       => 'ENUM',
                'constraint' => ['A', 'A-', 'B+', 'B'],
                'default'    => 'B',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['user_params', 'course_params']);
        $this->forge->addForeignKey('user_params', 'users', 'registration_main', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('course_params', 'courses', 'course_main', 'CASCADE', 'CASCADE');
        $this->forge->createTable('taken_courses');
    }

    public function down()
    {
        $this->forge->dropTable('taken_courses');
    }
}
