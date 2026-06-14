<?php

namespace App\Controllers;

class Test extends BaseController
{
    public function hashPassword()
    {
        $password = 'password_baru';
        $hash = password_hash($password, PASSWORD_DEFAULT);

        echo "Password hash: " . $hash;
    }
}
