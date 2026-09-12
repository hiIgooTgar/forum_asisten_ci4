<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdministratorsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username'       => 'admin1',
                'full_name'      => 'Super Admin Utama',
                'email'          => 'admin1@amikom.ac.id',
                'password'       => password_hash('password123', PASSWORD_BCRYPT),
                'role'           => 'admin',
                'profile'        => 'default.jpg',
                'is_active'      => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'username'       => 'bendahara',
                'full_name'      => 'Siti Aminah, S.E.',
                'email'          => 'treasurer@amikom.ac.id',
                'password'       => password_hash('password123', PASSWORD_BCRYPT),
                'role'           => 'treasurer',
                'profile'        => 'default.jpg',
                'is_active'      => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'username'       => 'sekretaris',
                'full_name'      => 'Budi Raharjo, M.Kom.',
                'email'          => 'secretary@amikom.ac.id',
                'password'       => password_hash('password123', PASSWORD_BCRYPT),
                'role'           => 'secretary',
                'profile'        => 'default.jpg',
                'is_active'      => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'username'       => 'sdm_staff',
                'full_name'      => 'Dewi Lestari, S.Psi.',
                'email'          => 'sdm@amikom.ac.id',
                'password'       => password_hash('password123', PASSWORD_BCRYPT),
                'role'           => 'sdm',
                'profile'        => 'default.jpg',
                'is_active'      => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'username'       => 'admin2',
                'full_name'      => 'Admin Operasional',
                'email'          => 'admin2@amikom.ac.id',
                'password'       => password_hash('password123', PASSWORD_BCRYPT),
                'role'           => 'admin',
                'profile'        => 'default.jpg',
                'is_active'      => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('administrators')->insertBatch($data);
    }
}
