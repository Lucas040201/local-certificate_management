<?php

namespace local_certificate_management\local\services\params;

use local_certificate_management\local\utils\MagicMethods;

class IssueCertificateParams
{
    use MagicMethods;

    public function __construct(
        protected $templateId,
        protected $userId,
        protected $courseId
    )
    {
    }
}