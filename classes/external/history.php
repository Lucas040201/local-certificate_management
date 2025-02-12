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
use local_certificate_management\local\services\HistoryService;
use local_certificate_management\local\services\CertificateService;
use local_certificate_management\local\services\params\IssueCertificateParams;

class history extends external_api
{
    /**
     * Describes the parameters for change_time.
     *
     * @return external_function_parameters
     * @since Moodle 3.3
     */
    public static function get_grade_history_parameters(): external_function_parameters
    {
        return new external_function_parameters(
            array(
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
    public static function get_grade_history_returns(): external_single_structure
    {
        return new external_single_structure(
            array(
                'history' => new external_value(PARAM_TEXT),
            )
        );
    }

    public static function get_grade_history(
        int    $courseId,
        int    $userId
    )
    {
        try {
            return HistoryService::getService()->getHistoryUrl(
                $courseId,
                $userId
            );
        } catch (Throwable $exception) {
            $statusCode = 500;

            if ($exception instanceof RuntimeException || $exception instanceof invalid_parameter_exception) {
                $statusCode = 400;
            }

            http_response_code($statusCode);

            throw new Exception($exception->getMessage() . ':' . $exception->getTraceAsString());
        }
    }
    /**
     * Describes the parameters for change_time.
     *
     * @return external_function_parameters
     * @since Moodle 3.3
     */
    public static function issue_grade_history_parameters(): external_function_parameters
    {
        return new external_function_parameters(
            array(
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
    public static function issue_grade_history_returns(): external_single_structure
    {
        return new external_single_structure(
            array(
                'history' => new external_value(PARAM_TEXT),
            )
        );
    }

    public static function issue_grade_history(
        int    $courseId,
        int    $userId
    )
    {
        try {
            return HistoryService::getService()->issueHistory(
                $courseId,
                $userId
            );
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