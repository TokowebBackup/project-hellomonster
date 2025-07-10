<?php

namespace App\Controllers;

class Language extends BaseController
{
    public function switch()
    {
        $lang = $this->request->getPost('lang');
        $session = session();
        $session->set('lang', $lang);
        return redirect()->back();
    }
}
