<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdministratorsSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            [
                'administrator_main' => 'adm_main_01',
                'username'           => 'superadmin',
                'full_name'          => 'Admin Utama FA',
                'email'              => 'admin@fa.amikompurwokerto.ac.id',
                'password'           => password_hash('password123', PASSWORD_BCRYPT),
                'role'               => 'admin',
                'profile'            => 'profile-default.png',
                'is_active'          => 1,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
                'administrator_main' => 'adm_main_02',
                'username'           => 'bendahara',
                'full_name'          => 'Budi Bendahara, S.Kom.',
                'email'              => 'treasurer@fa.amikompurwokerto.ac.id',
                'password'           => password_hash('password123', PASSWORD_BCRYPT),
                'role'               => 'treasurer',
                'profile'            => 'profile-default.png',
                'is_active'          => 1,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
                'administrator_main' => 'adm_main_03',
                'username'           => 'sekretaris',
                'full_name'          => 'Siti Sekretaris, S.Kom.',
                'email'              => 'secretary@fa.amikompurwokerto.ac.id',
                'password'           => password_hash('password123', PASSWORD_BCRYPT),
                'role'               => 'secretary',
                'profile'            => 'profile-default.png',
                'is_active'          => 1,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
                'administrator_main' => 'adm_main_04',
                'username'           => 'sdm_fa',
                'full_name'          => 'Rahmat Divisi SDM',
                'email'              => 'sdm@fa.amikompurwokerto.ac.id',
                'password'           => password_hash('password123', PASSWORD_BCRYPT),
                'role'               => 'sdm',
                'profile'            => 'profile-default.png',
                'is_active'          => 1,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
                'administrator_main' => 'adm_main_05',
                'username'           => 'co_sdm',
                'full_name'          => 'Dewi Coordinator SDM',
                'email'              => 'dewi.sdm@fa.amikompurwokerto.ac.id',
                'password'           => password_hash('password123', PASSWORD_BCRYPT),
                'role'               => 'sdm',
                'profile'            => 'profile-default.png',
                'is_active'          => 1,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
        ];

        $this->db->table('administrators')->insertBatch($data);
    }
}
