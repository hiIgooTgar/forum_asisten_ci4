<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SystemEventSettingsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'event_key'   => 'RECRUITMENT_GEN_2024_ODD',
                'event_name'  => 'Seleksi Asisten Praktikum Ganjil 2024/2025',
                'is_active'   => 1,
                'start_at'    => '2024-08-01 08:00:00',
                'end_at'      => '2024-09-30 23:59:59',
                'description' => 'Pendaftaran Asisten Praktikum Periode Semester Ganjil 2024/2025',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'event_key'   => 'RECRUITMENT_GEN_2024_EVEN',
                'event_name'  => 'Seleksi Asisten Praktikum Genap 2024/2025',
                'is_active'   => 0,
                'start_at'    => '2025-01-10 08:00:00',
                'end_at'      => '2025-02-28 23:59:59',
                'description' => 'Pendaftaran Asisten Praktikum Periode Semester Genap 2024/2025',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'event_key'   => 'MICROTEACHING_PHASE',
                'event_name'  => 'Jadwal Tes Microteaching & Wawancara',
                'is_active'   => 0,
                'start_at'    => '2024-10-05 08:00:00',
                'end_at'      => '2024-10-15 17:00:00',
                'description' => 'Pelaksanaan Ujian Mengajar dan Wawancara Calon Asisten',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'event_key'   => 'FINAL_ANNOUNCEMENT',
                'event_name'  => 'Pengumuman Kelulusan Akhir Asisten',
                'is_active'   => 0,
                'start_at'    => '2024-10-20 10:00:00',
                'end_at'      => '2024-10-25 23:59:59',
                'description' => 'Pengumuman Hasil Akhir Asisten Praktikum Diterima',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'event_key'   => 'ASST_BRIEFING',
                'event_name'  => 'Pembekalan & Briefing Asisten Terpilih',
                'is_active'   => 0,
                'start_at'    => '2024-10-28 09:00:00',
                'end_at'      => '2024-10-28 15:00:00',
                'description' => 'Briefing Tugas & Pembagian Honor Asisten Praktikum',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('system_event_settings')->insertBatch($data);
    }
}
