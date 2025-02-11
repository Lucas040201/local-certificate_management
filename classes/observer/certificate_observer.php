<?php

namespace local_certificate_management\observer;

use core\event\base;
use core\check\performance\debugging;
use local_certificate_management\event\debug_log;
use local_certificate_management\local\services\PdfService;

class certificate_observer
{
    public static function generate_second_page(base $event): void
    {
        PdfService::getService()->generateGradePdf($event->get_data());
    }
}