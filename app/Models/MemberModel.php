<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $table = 'members';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'phone', 'email', 'activation_token', 'is_active', 'password', 'birthdate', 'country', 'city', 'address'];
}
