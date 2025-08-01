<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function __construct()
    {
        helper('settings');
    }

    public function homeIndex()
    {
        $logo_html = get_setting('logo_website');
        preg_match('/src="([^"]+)"/', $logo_html, $matches);
        $logo_src = $matches[1] ?? '';
        $data = [
            'logo_src' => $logo_src,
            'version' => "v" . env('app.version'),
        ];
        // return $this->response->setBody("Welcome to Hellomonster {$version}!");
        return view('homepage', $data);
    }

    public function waiverIndex()
    {
        $logo_html = get_setting('logo_website');
        preg_match('/src="([^"]+)"/', $logo_html, $matches);
        $logo_src = $matches[1] ?? '';

        // Bersihkan session terkait waiver (jika mau dianggap start baru)
        if (session()->has('waiver_step_1')) {
            session()->remove('waiver_step_1');
        }

        if (session()->has('waiver_step_2')) {
            session()->remove('waiver_step_2');
        }

        if (session()->has('waiver_member_id')) {
            session()->remove('waiver_member_id');
        }


        $data = [
            'logo_src' => $logo_src,
        ];
        return view('home', $data);
    }
}
