<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFacultiesTable extends Migration
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
            'faculty_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'unique'     => true,
            ],
            'faculty_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'faculty_description' => [
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
        $this->forge->createTable('faculties');
    }

    public function down()
    {
        $this->forge->dropTable('faculties');
    }
}
