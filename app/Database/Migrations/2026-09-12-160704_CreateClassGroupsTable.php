<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClassGroupsTable extends Migration
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
            'class_main' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
            ],
            'study_program_params' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'class_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'academic_year' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
            ],
            'is_active' => [
                'type'       => 'BOOLEAN',
                'default'    => true,
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
        $this->forge->addUniqueKey(['study_program_params', 'class_name']);
        $this->forge->addForeignKey('study_program_params', 'study_programs', 'program_main', 'CASCADE', 'CASCADE');
        $this->forge->createTable('class_groups');
    }

    public function down()
    {
        $this->forge->dropTable('class_groups');
    }
}
