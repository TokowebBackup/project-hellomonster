<?php
// app/Models/ChildrenModel.php

namespace App\Models;

use CodeIgniter\Model;

class ChildrenModel extends Model
{
    protected $table = 'children';
    protected $primaryKey = 'id';
    protected $allowedFields = ['member_uuid', 'name', 'birthdate', 'gender', 'created_at'];
    protected $useTimestamps = false;
}
