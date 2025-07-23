<?php

use App\Models\SettingModel;

if (!function_exists('get_setting')) {
    function get_setting($key)
    {
        $model = new SettingModel();
        return $model->get($key);
    }
}
