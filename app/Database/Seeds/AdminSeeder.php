<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $password = password_hash('xaxbbczczaaxaa', PASSWORD_DEFAULT);

        $data = [
            'username' => 'admin',
            'email'    => 'admin@hellomonster.id',
            'password' => $password,
            'name'     => 'Administrator',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->table('admins')->insert($data);
    }
}
