<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $allowedFields = ['key_name', 'content'];

    public function get($key)
    {
        $row = $this->where('key_name', $key)->first();
        return $row ? $row['content'] : null;
    }

    public function setSetting($key, $content)
    {
        $data = ['key_name' => $key, 'content' => $content];
        $existing = $this->where('key_name', $key)->first();
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->insert($data);
        }
    }
}
