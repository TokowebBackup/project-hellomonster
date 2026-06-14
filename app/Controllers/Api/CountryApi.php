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
            $client = \Config\Services::curlrequest([
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ],
                'http_errors' => false,
                'allow_redirects' => true,
                'verify' => false // PENTING: Mematikan verifikasi SSL agar lolos dari blokir shared hosting
            ]);

            $url = 'https://countriesnow.space/api/v1/countries/iso';
            $response = $client->get($url);

            $countries = [];

            // 1. Coba ambil dari API terlebih dahulu
            if ($response->getStatusCode() === 200) {
                $rawData = json_decode($response->getBody(), true);
                if (isset($rawData['data']) && is_array($rawData['data'])) {
                    $countries = array_filter(array_map(function ($item) {
                        return $item['name'] ?? null;
                    }, $rawData['data']));
                }
            }

            // 2. JIKA API DIBLOKIR HOSTING, DAFTAR LENGKAP SELURUH DUNIA INI AKAN MENYELAMATKAN FORM KAMU
            if (empty($countries)) {
                $countries = [
                    "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan",
                    "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi",
                    "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czechia",
                    "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia",
                    "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana",
                    "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Ivory Coast", "Jamaica", "Japan", "Jordan",
                    "Kazakhstan", "Kenya", "Kiribati", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg",
                    "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar",
                    "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea", "North Macedonia", "Norway", "Oman",
                    "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar",
                    "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden", "Switzerland", "Syria",
                    "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu",
                    "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
                ];
            }

            // 3. Urutkan abjad
            sort($countries);
            
            // 4. Pastikan Indonesia tetap berada di urutan paling atas demi kenyamanan user
            if (($key = array_search('Indonesia', $countries)) !== false) {
                unset($countries[$key]);
                array_unshift($countries, 'Indonesia');
            }

            return $this->respond(array_values($countries));
        } catch (\Throwable $e) {
            // Jika terjadi crash fatal internal, lempar data darurat agar front-end tidak kosong
            $emergency = ["Indonesia", "Malaysia", "Singapore", "Australia", "United States", "United Kingdom"];
            return $this->respond($emergency);
        }
    }

    public function getCountryCodeFromName($countryName)
    {
        // Fungsi pencarian cadangan sederhana
        $countries = [
            'indonesia' => 'id', 'malaysia' => 'my', 'singapore' => 'sg', 
            'australia' => 'au', 'united states' => 'us', 'united kingdom' => 'gb'
        ];
        return $countries[strtolower($countryName)] ?? 'id';
    }
}