<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCompanyApplicationsTable extends Migration
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
            'company_application_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
            ],
            'application_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'application_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'application_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'logo' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'logo-fa-default.png',
            ],
            'logo_white' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'logo-fa-white-default.png',
            ],
            'logo_sidebar' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'logo-fa-sidebar.png',
            ],
            'favicon' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'favicon-fa-default.ico',
            ],
            'phone_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'instagram_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'youtube_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'linkedin_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'tiktok_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'website_url' => [
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
        $this->forge->createTable('company_applications');
    }

    public function down()
    {
        $this->forge->dropTable('company_applications');
    }
}
