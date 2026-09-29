<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserDocumentsSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $randomCode = 'document_' . strtoupper(bin2hex(random_bytes(32)));

        $data = [
            ['document_code' => $randomCode, 'user_id' => 1, 'student_card_file' => 'ktm_1.pdf', 'application_letter_file' => 'lamaran_1.pdf', 'cv_file' => 'cv_1.pdf', 'latest_transcript_file' => 'transkrip_1.pdf', 'statement_letter_file' => 'pernyataan_1.pdf', 'registration_form_file' => 'form_1.pdf', 'created_at' => $now, 'updated_at' => $now],
            ['document_code' => $randomCode, 'user_id' => 2, 'student_card_file' => 'ktm_2.pdf', 'application_letter_file' => 'lamaran_2.pdf', 'cv_file' => 'cv_2.pdf', 'latest_transcript_file' => 'transkrip_2.pdf', 'statement_letter_file' => 'pernyataan_2.pdf', 'registration_form_file' => 'form_2.pdf', 'created_at' => $now, 'updated_at' => $now],
            ['document_code' => $randomCode, 'user_id' => 3, 'student_card_file' => 'ktm_3.pdf', 'application_letter_file' => 'lamaran_3.pdf', 'cv_file' => 'cv_3.pdf', 'latest_transcript_file' => 'transkrip_3.pdf', 'statement_letter_file' => 'pernyataan_3.pdf', 'registration_form_file' => 'form_3.pdf', 'created_at' => $now, 'updated_at' => $now],
            ['document_code' => $randomCode, 'user_id' => 4, 'student_card_file' => 'ktm_4.pdf', 'application_letter_file' => 'lamaran_4.pdf', 'cv_file' => 'cv_4.pdf', 'latest_transcript_file' => 'transkrip_4.pdf', 'statement_letter_file' => 'pernyataan_4.pdf', 'registration_form_file' => 'form_4.pdf', 'created_at' => $now, 'updated_at' => $now],
            ['document_code' => $randomCode, 'user_id' => 5, 'student_card_file' => 'ktm_5.pdf', 'application_letter_file' => 'lamaran_5.pdf', 'cv_file' => 'cv_5.pdf', 'latest_transcript_file' => 'transkrip_5.pdf', 'statement_letter_file' => 'pernyataan_5.pdf', 'registration_form_file' => 'form_5.pdf', 'created_at' => $now, 'updated_at' => $now],
        ];

        $this->db->table('user_documents')->insertBatch($data);
    }
}
