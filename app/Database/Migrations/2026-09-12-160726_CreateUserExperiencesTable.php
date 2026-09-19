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
            'experience_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
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
                'constraint' => ['work', 'organizational', 'teaching_assistant', 'volunteering', "certification", "competition", "project", "community_service", 'other'],
                'default'    => 'work',
            ],
            'year_occurred' => [
                'type' => 'YEAR',
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
