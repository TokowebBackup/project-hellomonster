<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $table = 'members';
    protected $primaryKey = 'id';
    protected $allowedFields = ['uuid', 'name', 'phone', 'email', 'activation_token', 'is_active', 'password', 'birthdate', 'country', 'city', 'address', 'agree_terms', 'created_at'];
}
