<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CompanyApplicationSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $randomCode = 'FA_' . strtoupper(bin2hex(random_bytes(16)));

        $data = [
            'id'                       => 1,
            'company_application_code' => $randomCode,
            'application_name'         => 'Penerimaan Asisten Laboratorium',
            'application_title'        => 'Portal Pendaftaran Calon Anggota Asisten Laboratorium & Akademik',
            'application_description'  => 'Sistem informasi resmi pendaftaran, seleksi, dan verifikasi berkas calon asisten laboratorium. Portal ini memfasilitasi proses pendaftaran transparan untuk pengembangan akademik dan kepemimpinan mahasiswa.',
            'logo'                     => 'logo-fa.png',
            'logo_white'               => 'logo-fa-white.png',
            'logo_sidebar'             => 'logo-fa-white-v2.png',
            'favicon'                  => 'favicon-fa.ico',
            'phone_number'             => '6281234567890',
            'email'                    => 'sdm.lab@universitas.ac.id',
            'address'                  => 'Gedung Laboratorium Terpadu Lantai 3, Jl. Raya Kampus Utama No. 12',
            'instagram_url'            => 'https://instagram.com/asisten_lab_official',
            'youtube_url'              => 'https://youtube.com/@asisten_lab_official',
            'linkedin_url'             => 'https://linkedin.com/company/asisten-lab-official',
            'tiktok_url'               => 'https://tiktok.com/@asisten_lab_official',
            'website_url'              => 'https://laboratorium.ac.id',
            'created_at'               => $now,
            'updated_at'               => $now,
        ];

        $this->db->table('company_applications')->emptyTable();
        $this->db->table('company_applications')->insert($data);
    }
}
