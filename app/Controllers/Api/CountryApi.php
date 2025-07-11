<?php

/**
 * @author PujiErmanto<pujiermanto@gmail.com> | AKA Vickeerneess | AKA Kolega Iwan Fals
 * @return _interfaces
 */

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class CountryApi extends ResourceController
{
    public function index()
    {
        try {
            $client = \Config\Services::curlrequest();
            // PAKAI INI YANG BENAR
            $response = $client->get('https://restcountries.com/v3.1/all?fields=name');

            if ($response->getStatusCode() !== 200) {
                return $this->failServerError('API negara tidak merespons dengan benar');
            }

            $body = $response->getBody();
            $rawData = json_decode($body, true);

            if (!is_array($rawData)) {
                return $this->failServerError('Data yang diterima bukan array');
            }

            // Ambil nama negara sebagai flat array
            $countries = array_filter(array_map(function ($item) {
                return $item['name']['common'] ?? null;
            }, $rawData));

            return $this->respond(array_values($countries));
        } catch (\Throwable $e) {
            log_message('error', 'Gagal ambil negara: ' . $e->getMessage());
            return $this->failServerError('Terjadi kesalahan saat mengambil data negara');
        }
    }
}
