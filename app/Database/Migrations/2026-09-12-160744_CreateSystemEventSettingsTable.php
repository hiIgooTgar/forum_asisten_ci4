<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSystemEventSettingsTable extends Migration
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
            'parent_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'event_key' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'event_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'category' => [
                'type'       => 'ENUM',
                'constraint' => ['recruitment_period', 'recruitment_stage', 'general_announcement'],
                'default'    => 'recruitment_stage',
            ],
            'is_active' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'is_default' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'status_override' => [
                'type'       => 'ENUM',
                'constraint' => ['auto', 'coming_soon', 'open', 'closed'],
                'default'    => 'auto',
            ],
            'start_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'end_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'action_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
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
        $this->forge->addForeignKey('parent_id', 'system_event_settings', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('system_event_settings');
    }

    public function down()
    {
        $this->forge->dropTable('system_event_settings');
    }
}
