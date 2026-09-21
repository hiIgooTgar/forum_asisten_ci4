<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class FileViewer extends BaseController
{
    public function show(string $folder, string $subfolder, string $fileName)
    {
        $session          = session();
        $isAdmin          = $session->get('is_admin_logged_in') && $session->get('admin_id');
        $isStudent        = $session->get('is_student_logged_in');
        $sessStudentNum   = (string) $session->get('student_number');

        if (!$isAdmin && !$isStudent) {
            $this->response->setStatusCode(403);
            return view('errors/html/error_page_403', [
                'errorMessage' => 'Anda harus melakukan login terlebih dahulu untuk mengakses berkas ini.'
            ]);
        }

        $folder    = basename($folder);
        $subfolder = basename($subfolder);
        $fileName  = basename($fileName);

        if (!$isAdmin) {
            if (empty($sessStudentNum)) {
                $this->response->setStatusCode(403);
                return view('errors/html/error_page_403', [
                    'errorMessage' => 'Sesi login mahasiswa Anda tidak valid atau telah kadaluarsa.'
                ]);
            }

            $isFolderOwner = (strpos($subfolder, $sessStudentNum) === 0);
            $isFileOwner   = (strpos($fileName, $sessStudentNum) !== false);

            if (!$isFolderOwner && !$isFileOwner) {
                log_message('warning', "Akses ilegal ditolak: Mahasiswa NIM {$sessStudentNum} mencoba membuka berkas milik {$subfolder}");

                $this->response->setStatusCode(403);
                return view('errors/html/error_page_403', [
                    'errorMessage' => 'Anda tidak memiliki hak akses untuk membaca atau mengunduh berkas ini.'
                ]);
            }
        }

        $filePath = WRITEPATH . "uploads/{$folder}/{$subfolder}/{$fileName}";
        if (!is_file($filePath) || !file_exists($filePath)) {
            throw PageNotFoundException::forPageNotFound('Berkas tidak ditemukan.');
        }

        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $fileName . '"')
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->setHeader('Pragma', 'no-cache')
            ->setBody(file_get_contents($filePath));
    }
}
