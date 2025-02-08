<?php

namespace local_certificate_management\local\services;

use tool_certificate\template;
use local_certificate_management\local\services\params\IssueCertificateParams;

class CertificateService
{
    private static ?CertificateService $service = null;

    public function issueCertificate(
        IssueCertificateParams $params
    )
    {
        $template = template::instance($params->templateId);
        $certificateId = $template->issue_certificate(
            userid: $params->userId,
            courseid: $params->courseId,
        );

        return [
            'certificateId' => $certificateId,
        ];
    }

    public static function getService(): CertificateService
    {
        if (self::$service === null) {
            self::$service = new self();
        }

        return self::$service;
    }
}