<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserExperiencesTable extends Migration
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
            'user_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'organization_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'experience_type' => [
                'type'       => 'ENUM',
                'constraint' => ['work', 'organizational', 'teaching_assistant', 'volunteering', 'other'],
                'default'    => 'work',
            ],
            'start_date' => [
                'type' => 'DATE',
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'is_current' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_experiences');
    }

    public function down()
    {
        $this->forge->dropTable('user_experiences');
    }
}
