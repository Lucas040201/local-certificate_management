<?php

namespace local_certificate_management\local\services;

class HistoryService
{
    private static ?HistoryService $service = null;


    public static function getService(): HistoryService
    {
        if (self::$service === null) {
            self::$service = new self();
        }

        return self::$service;
    }
}