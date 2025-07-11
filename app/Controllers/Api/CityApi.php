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

            $response = $client->get('https://countriesnow.space/api/v0.1/countries/cities/q', [
                'query' => ['country' => $country]
            ]);

            $body = $response->getBody();
            $json = json_decode($body, true);

            if (isset($json['data']) && is_array($json['data'])) {
                return $this->response->setJSON($json['data']);
            }

            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_GATEWAY)
                ->setJSON(['error' => 'Gagal mengambil data kota']);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON(['error' => $e->getMessage()]);
        }
    }
}
