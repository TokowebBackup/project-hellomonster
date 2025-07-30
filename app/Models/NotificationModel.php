<?php
// app/Models/NotificationModel.php
namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $allowedFields = ['title', 'message', 'type', 'is_read', 'created_at'];
    protected $useTimestamps = true;
}
