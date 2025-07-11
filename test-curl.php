<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://restcountries.com/v3.1/all?fields=name");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if ($response === false) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    echo 'Curl success: ';
    print_r(json_decode($response, true));
}

curl_close($ch);
