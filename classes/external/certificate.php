<?php

namespace local_certificate_management\external;

use Throwable;
use Exception;
use RuntimeException;
use core_external\external_api;
use invalid_parameter_exception;
use core_external\external_value;
use core_external\external_single_structure;
use core_external\external_function_parameters;
use local_certificate_management\local\services\CertificateService;
use local_certificate_management\local\services\params\IssueCertificateParams;

class certificate extends external_api
{
    /**
     * Describes the parameters for change_time.
     *
     * @return external_function_parameters
     * @since Moodle 3.3
     */
    public static function issue_certificate_parameters(): external_function_parameters
    {
        return new external_function_parameters(
            array(
                'templateId' => new external_value(PARAM_INT, 'Template Id', VALUE_REQUIRED),
                'courseId' => new external_value(PARAM_INT, 'Course Id', VALUE_REQUIRED),
                'userId' => new external_value(PARAM_INT, 'User Id', VALUE_REQUIRED),
            )
        );
    }

    /**
     * Describes the change_time return value.
     *
     * @return external_function_parameters
     * @since Moodle 3.3
     */
    public static function issue_certificate_returns(): external_single_structure
    {
        return new external_single_structure(
            array(
                'certificateId' => new external_value(PARAM_INT),
            )
        );
    }

    public static function issue_certificate(
        int    $templateId,
        int    $courseId,
        int    $userId
    )
    {
        try {
            $params = new IssueCertificateParams(
                $templateId,
                $userId,
                $courseId
            );
            return CertificateService::getService()->issueCertificate($params);
        } catch (Throwable $exception) {
            $statusCode = 500;

            if ($exception instanceof RuntimeException || $exception instanceof invalid_parameter_exception) {
                $statusCode = 400;
            }

            http_response_code($statusCode);

            throw new Exception($exception->getMessage() . ':' . $exception->getTraceAsString());
        }
    }
}