<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SystemEventSettingsSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('system_event_settings')->insert([
            'event_key'       => 'REGISTRATION_CLOSED_DEFAULT',
            'event_name'      => 'Pendaftaran Ditutup',
            'category'        => 'general_announcement',
            'is_active'       => 0,
            'is_default'      => 1,
            'status_override' => 'closed',
            'description'     => 'Saat ini tidak ada pendaftaran asisten yang sedang dibuka.',
            'created_at'      => $now,
            'updated_at'      => $now,
        ]);

        $this->db->table('system_event_settings')->insert([
            'event_key'       => 'RECRUITMENT_GEN_2024_ODD',
            'event_name'      => 'Seleksi Asisten Praktikum Ganjil 2024/2025',
            'category'        => 'recruitment_period',
            'is_active'       => 1,
            'is_default'      => 0,
            'status_override' => 'auto',
            'start_at'        => '2024-08-01 08:00:00',
            'end_at'          => '2024-09-30 23:59:59',
            'description'     => 'Pendaftaran Asisten Praktikum Periode Semester Ganjil 2024/2025',
            'action_url'      => '/register',
            'created_at'      => $now,
            'updated_at'      => $now,
        ]);

        $parentId = $this->db->insertID();

        $stages = [
            [
                'parent_id'       => $parentId,
                'event_key'       => 'MICROTEACHING_PHASE_2024_ODD',
                'event_name'      => 'Jadwal Tes Microteaching & Wawancara',
                'category'        => 'recruitment_stage',
                'is_active'       => 0,
                'is_default'      => 0,
                'status_override' => 'auto',
                'start_at'        => '2024-10-05 08:00:00',
                'end_at'          => '2024-10-15 17:00:00',
                'description'     => 'Pelaksanaan Ujian Mengajar dan Wawancara Calon Asisten',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'parent_id'       => $parentId,
                'event_key'       => 'FINAL_ANNOUNCEMENT_2024_ODD',
                'event_name'      => 'Pengumuman Kelulusan Akhir Asisten',
                'category'        => 'recruitment_stage',
                'is_active'       => 0,
                'is_default'      => 0,
                'status_override' => 'auto',
                'start_at'        => '2024-10-20 10:00:00',
                'end_at'          => '2024-10-25 23:59:59',
                'description'     => 'Pengumuman Hasil Akhir Asisten Praktikum Diterima',
                'action_url'      => '/announcements',
                'created_at'      => $now,
                'updated_at'      => $now,
            ]
        ];

        $this->db->table('system_event_settings')->insertBatch($stages);
    }
}
