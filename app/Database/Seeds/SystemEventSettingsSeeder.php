<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SystemEventSettingsSeeder extends Seeder
{

    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $data  = [
            [
                'system_event_main' => 'evt_rec_2026_genap',
                'parent_id'         => null,
                'event_key'         => 'recruitment_2026_even',
                'event_name'        => 'Pendaftaran Calon Asisten Semester Genap 2025/2026',
                'category'          => 'recruitment_period',
                'is_active'         => 1,
                'is_default'        => 1,
                'status_override'   => 'open',
                'start_at'          => '2026-02-01 00:00:00',
                'end_at'            => '2026-03-30 23:59:59',
                'description'       => 'Periode rekrutmen terbuka untuk mahasiswa aktif.',
                'action_url'        => '/auth/register',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'system_event_main' => 'evt_stage_adm',
                'parent_id'         => 1,
                'event_key'         => 'stage_administrative',
                'event_name'        => 'Seleksi Berkas Administrasi',
                'category'          => 'recruitment_stage',
                'is_active'         => 1,
                'is_default'        => 0,
                'status_override'   => 'auto',
                'start_at'          => '2026-02-01 00:00:00',
                'end_at'            => '2026-03-05 23:59:59',
                'description'       => 'Verifikasi dokumen dan syarat administrasi pendaftar.',
                'action_url'        => null,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'system_event_main' => 'evt_stage_test',
                'parent_id'         => 1,
                'event_key'         => 'stage_microteaching',
                'event_name'        => 'Tes Microteaching & Wawancara',
                'category'          => 'recruitment_stage',
                'is_active'         => 1,
                'is_default'        => 0,
                'status_override'   => 'coming_soon',
                'start_at'          => '2026-03-10 08:00:00',
                'end_at'            => '2026-03-15 17:00:00',
                'description'       => 'Ujian praktek mengajar dan sesi wawancara dengan penguji FA.',
                'action_url'        => null,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'system_event_main' => 'evt_announcement',
                'parent_id'         => 1,
                'event_key'         => 'stage_final_announcement',
                'event_name'        => 'Pengumuman Kelulusan Akhir',
                'category'          => 'general_announcement',
                'is_active'         => 0,
                'is_default'        => 0,
                'status_override'   => 'closed',
                'start_at'          => '2026-03-20 10:00:00',
                'end_at'            => '2026-03-25 23:59:59',
                'description'       => 'Pengumuman peserta yang diterima sebagai Asisten Praktikum.',
                'action_url'        => '/student/announcement',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'system_event_main' => 'evt_briefing',
                'parent_id'         => 1,
                'event_key'         => 'stage_briefing',
                'event_name'        => 'First Gathering & Briefing Asisten Baru',
                'category'          => 'general_announcement',
                'is_active'         => 0,
                'is_default'        => 0,
                'status_override'   => 'coming_soon',
                'start_at'          => '2026-03-28 09:00:00',
                'end_at'            => '2026-03-28 12:00:00',
                'description'       => 'Pertemuan perdana dan pembekalan untuk asisten terdaftar.',
                'action_url'        => null,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
        ];

        $this->db->table('system_event_settings')->insertBatch($data);
    }
}
