<?php

namespace App\Models;

use CodeIgniter\Model;

class SignatureModel extends Model
{
    protected $table = 'signatures';
    protected $primaryKey = 'id';
    protected $allowedFields = ['member_uuid', 'signature', 'signed_at'];
    public $timestamps = false;
}
