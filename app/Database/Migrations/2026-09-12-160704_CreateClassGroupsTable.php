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
            'study_program_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
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
        $this->forge->addUniqueKey(['study_program_id', 'class_name']);
        $this->forge->addForeignKey('study_program_id', 'study_programs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('class_groups');
    }

    public function down()
    {
        $this->forge->dropTable('class_groups');
    }
}
