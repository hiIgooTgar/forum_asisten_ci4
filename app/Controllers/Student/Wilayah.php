<?php

namespace App\Controllers\Student;

use CodeIgniter\RESTful\ResourceController;

class Wilayah extends ResourceController
{
    private function fetchApi($url)
    {
        $client = \Config\Services::curlrequest();
        try {
            $response = $client->request('GET', $url, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    'Accept'     => 'application/json',
                ],
                'timeout'     => 10,
                'http_errors' => false
            ]);

            $body = json_decode($response->getBody(), true);

            return $this->response
                ->setStatusCode($response->getStatusCode())
                ->setJSON($body);
        } catch (\Exception $e) {
            log_message('error', 'Wilayah API Error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => 'Gagal terhubung ke API Wilayah: ' . $e->getMessage()
            ]);
        }
    }

    public function provinces()
    {
        return $this->fetchApi('https://wilayah.id/api/provinces.json');
    }

    public function regencies($provCode = null)
    {
        if (!$provCode) return $this->failNotFound('Kode provinsi diperlukan');
        return $this->fetchApi("https://wilayah.id/api/regencies/{$provCode}.json");
    }

    public function districts($regCode = null)
    {
        if (!$regCode) return $this->failNotFound('Kode kabupaten diperlukan');
        return $this->fetchApi("https://wilayah.id/api/districts/{$regCode}.json");
    }

    public function villages($distCode = null)
    {
        if (!$distCode) return $this->failNotFound('Kode kecamatan diperlukan');
        return $this->fetchApi("https://wilayah.id/api/villages/{$distCode}.json");
    }
}
