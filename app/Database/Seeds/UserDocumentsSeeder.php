<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserDocumentsSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            [
                'document_main'            => 'doc_main_001',
                'user_params'              => 'user_reg_001',
                'student_card_file'        => 'ktm_22110001.pdf',
                'application_letter_file'  => 'surat_lamaran_22110001.pdf',
                'cv_file'                  => 'cv_22110001.pdf',
                'latest_transcript_file'   => 'transkrip_22110001.pdf',
                'statement_letter_file'    => 'surat_pernyataan_22110001.pdf',
                'registration_form_file'   => 'form_22110001.pdf',
                'created_at'               => $now,
                'updated_at'               => $now,
            ],
            [
                'document_main'            => 'doc_main_002',
                'user_params'              => 'user_reg_002',
                'student_card_file'        => 'ktm_22110002.pdf',
                'application_letter_file'  => 'surat_lamaran_22110002.pdf',
                'cv_file'                  => 'cv_22110002.pdf',
                'latest_transcript_file'   => 'transkrip_22110002.pdf',
                'statement_letter_file'    => 'surat_pernyataan_22110002.pdf',
                'registration_form_file'   => 'form_22110002.pdf',
                'created_at'               => $now,
                'updated_at'               => $now,
            ],
            [
                'document_main'            => 'doc_main_003',
                'user_params'              => 'user_reg_003',
                'student_card_file'        => 'ktm_22120015.pdf',
                'application_letter_file'  => 'surat_lamaran_22120015.pdf',
                'cv_file'                  => 'cv_22120015.pdf',
                'latest_transcript_file'   => 'transkrip_22120015.pdf',
                'statement_letter_file'    => 'surat_pernyataan_22120015.pdf',
                'registration_form_file'   => 'form_22120015.pdf',
                'created_at'               => $now,
                'updated_at'               => $now,
            ],
            [
                'document_main'            => 'doc_main_004',
                'user_params'              => 'user_reg_004',
                'student_card_file'        => 'ktm_23010008.pdf',
                'application_letter_file'  => 'surat_lamaran_23010008.pdf',
                'cv_file'                  => 'cv_23010008.pdf',
                'latest_transcript_file'   => 'transkrip_23010008.pdf',
                'statement_letter_file'    => 'surat_pernyataan_23010008.pdf',
                'registration_form_file'   => 'form_23010008.pdf',
                'created_at'               => $now,
                'updated_at'               => $now,
            ],
            [
                'document_main'            => 'doc_main_005',
                'user_params'              => 'user_reg_005',
                'student_card_file'        => 'ktm_22310003.pdf',
                'application_letter_file'  => null,
                'cv_file'                  => null,
                'latest_transcript_file'   => null,
                'statement_letter_file'    => null,
                'registration_form_file'   => null,
                'created_at'               => $now,
                'updated_at'               => $now,
            ],
        ];

        $this->db->table('user_documents')->insertBatch($data);
    }
}
