<?php

require_once __DIR__ . '/Model.php';

class Setting extends Model {
    protected $table = 'settings';

    public function getValue($key, $default = null) {
        $setting = $this->firstWhere('setting_key = :key', ['key' => $key]);
        return $setting ? $setting['setting_value'] : $default;
    }

    public function setValue($key, $value) {
        $existing = $this->firstWhere('setting_key = :key', ['key' => $key]);
        if ($existing) {
            return $this->update($existing['id'], ['setting_value' => $value]);
        } else {
            return $this->create([
                'setting_key'   => $key,
                'setting_value' => $value
            ]);
        }
    }

    public function getAllAsAssoc() {
        $all = $this->all();
        $res = [];
        foreach ($all as $item) {
            $res[$item['setting_key']] = $item['setting_value'];
        }
        return $res;
    }
}
