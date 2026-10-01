<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudyProgramsTable extends Migration
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
            'program_main' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
            ],
            'faculty_params' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'program_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
            ],
            'program_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'program_description' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'degree_level' => [
                'type'       => 'ENUM',
                'constraint' => ['D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'],
                'default'    => 'S1',
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
        $this->forge->addForeignKey('faculty_params', 'faculties', 'faculty_main', 'CASCADE', 'CASCADE');
        $this->forge->createTable('study_programs');
    }

    public function down()
    {
        $this->forge->dropTable('study_programs');
    }
}
