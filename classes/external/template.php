<?php

namespace local_certificate_management\external;

use Throwable;
use Exception;
use RuntimeException;
use core_external\external_api;
use invalid_parameter_exception;
use core_external\external_value;
use core_external\external_single_structure;
use core_external\external_multiple_structure;
use core_external\external_function_parameters;
use local_certificate_management\local\services\TemplateService;

class template extends external_api
{
    /**
     * Describes the parameters for change_time.
     *
     * @return external_function_parameters
     * @since Moodle 3.3
     */
    public static function retrieve_templates_parameters(): external_function_parameters
    {
        return new external_function_parameters([]);
    }

    /**
     * Describes the change_time return value.
     *
     * @return external_function_parameters
     * @since Moodle 3.3
     */
    public static function retrieve_templates_returns(): external_single_structure
    {
        return new external_single_structure(
            array(
                'templates' => new external_multiple_structure(new external_single_structure([
                    "id" => new external_value(PARAM_INT),
                    "name" => new external_value(PARAM_TEXT),
                    "contextid" => new external_value(PARAM_INT),
                    "shared" => new external_value(PARAM_INT),
                    "timecreated" => new external_value(PARAM_INT),
                    "timemodified" => new external_value(PARAM_INT),
                ])),
            ),
        );
    }

    public static function retrieve_templates()
    {
        try {
            return TemplateService::getService()->retrieveTemplates();
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