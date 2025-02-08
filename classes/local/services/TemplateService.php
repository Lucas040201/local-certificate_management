<?php

namespace local_certificate_management\local\services;

use local_certificate_management\local\repositories\TemplateRepository;

class TemplateService
{
    private static ?TemplateService $service = null;
    private TemplateRepository $repository;


    public function __construct()
    {
        $this->repository = new TemplateRepository();
    }

    public function retrieveTemplates()
    {
        return [
            'templates' => $this->repository->retrieveCertificate()
        ];
    }

    public static function getService(): TemplateService
    {
        if (self::$service === null) {
            self::$service = new self();
        }

        return self::$service;
    }
}