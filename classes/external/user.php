<?php

namespace local_certificate_management\external;

use Exception;
use Throwable;
use RuntimeException;
use core_external\external_api;
use invalid_parameter_exception;
use core_external\external_value;
use core_external\external_single_structure;
use core_external\external_multiple_structure;
use core_external\external_function_parameters;
use local_certificate_management\local\services\UsersService;

class user extends external_api
{
    /**
     * Describes the parameters for change_time.
     *
     * @return external_function_parameters
     * @since Moodle 3.3
     */
    public static function retrieve_users_parameters(): external_function_parameters
    {
        return new external_function_parameters(
            array(
                'courseId' => new external_value(PARAM_INT, 'Course Id', VALUE_REQUIRED),
                'limit' => new external_value(PARAM_INT, 'limit', VALUE_DEFAULT),
                'page' => new external_value(PARAM_INT, 'page', VALUE_DEFAULT),
                'search' => new external_value(PARAM_TEXT, 'search param', VALUE_DEFAULT),
                'sort' => new external_value(PARAM_TEXT, 'Sort order', VALUE_DEFAULT),
            )
        );
    }

    /**
     * Describes the change_time return value.
     *
     * @return external_function_parameters
     * @since Moodle 3.3
     */
    public static function retrieve_users_returns(): external_single_structure
    {
        return new external_single_structure(
            array(
                'users' => new external_multiple_structure(new external_single_structure([
                    "id" => new external_value(PARAM_INT),
                    "name" => new external_value(PARAM_TEXT),
                    "email" => new external_value(PARAM_TEXT),
                    "has_certificate" => new external_value(PARAM_BOOL),
                    "has_history" => new external_value(PARAM_BOOL),
                    "progress" => new external_value(PARAM_TEXT),
                ])),
                "total" => new external_value(PARAM_INT),
                "page" => new external_value(PARAM_INT),
            )
        );
    }

    public static function retrieve_users(
        int $courseId,
        int $limit = 30,
        int $page = 1,
        string $search = '',
        string $sort = 'ASC',
    )
    {
        try {
            return UsersService::getService()->retrieveUsers(
                $courseId,
                $search,
                $sort,
                $page,
                $limit
            );
        } catch(Throwable $exception){
            $statusCode = 500;

            if ($exception instanceof RuntimeException || $exception instanceof invalid_parameter_exception) {
                $statusCode = 400;
            }

            http_response_code($statusCode);

            throw new Exception($exception->getMessage() . ':' . $exception->getTraceAsString());
        }
    }

}