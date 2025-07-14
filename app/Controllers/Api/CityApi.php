<?php

/**
 * @author PujiErmanto<pujiermanto@gmail.com> | AKA Vickeerneess | AKA Kolega Iwan Fals
 * @return _interfaces
 */

namespace App\Controllers\Api;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class CityApi extends Controller
{
    public function index()
    {
        $country = $this->request->getPost('country');
        if (!$country) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setJSON(['error' => 'Negara tidak boleh kosong']);
        }

        try {
            $client = \Config\Services::curlrequest();

            $response = $client->get('https://www.emsifa.com/api-wilayah-indonesia/api/regencies/32.json');
            // contoh: ambil semua kota/kabupaten dari Jawa Barat (32)

            $body = $response->getBody();
            $json = json_decode($body, true);

            $cities = [];

            foreach ($json as $item) {
                $name = strtoupper($item['name']);

                // Cek apakah mengandung "KOTA" atau "KABUPATEN"
                if (str_contains($name, 'KOTA')) {
                    $cities[] = 'KOTA ' . trim(str_replace('KOTA', '', $name));
                } elseif (str_contains($name, 'KABUPATEN')) {
                    $cities[] = 'KAB. ' . trim(str_replace('KABUPATEN', '', $name));
                } else {
                    $cities[] = $name;
                }
            }

            sort($cities);

            return $this->response->setJSON($cities);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON(['error' => $e->getMessage()]);
        }
    }
}
