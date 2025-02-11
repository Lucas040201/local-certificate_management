<?php

namespace local_certificate_management\event;

use core\event\base;

class debug_log extends base
{

    protected function init()
    {
        $this->data['objecttable'] = null;
        $this->data['crud'] = 'c'; // 'c' (create), 'r' (read), 'u' (update), 'd' (delete)
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    public static function get_name(): string
    {
        return "Log de Debug Personalizado";
    }

    public function get_description(): string
    {
        return "Mensagem de depuração: " . $this->other['info'];
    }

    public function get_url(): \moodle_url
    {
        return new \moodle_url('/admin/tool/log/index.php');
    }
}