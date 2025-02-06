<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version metadata for the local_manage_enrollments plugin.
 *
 * @package   local_certificate_management
 * @copyright 2025 Lucas Mendes <lucas.mendes.dev@outlook.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

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
use local_certificate_management\local\services\CourseService;

class course extends external_api
{
    /**
     * Describes the parameters for change_time.
     *
     * @return external_function_parameters
     * @since Moodle 3.3
     */
    public static function retrieve_courses_parameters(): external_function_parameters
    {
        return new external_function_parameters(
            array(
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
    public static function retrieve_courses_returns(): external_single_structure
    {
        return new external_single_structure(
            array(
                'courses' => new external_multiple_structure(new external_single_structure([
                    "id" => new external_value(PARAM_INT),
                    "short_name" => new external_value(PARAM_TEXT),
                    "fullname" => new external_value(PARAM_TEXT),
                    "enrolled_users" => new external_value(PARAM_INT),
                ])),
                "total" => new external_value(PARAM_INT),
                "page" => new external_value(PARAM_INT),
            )
        );
    }

    public static function retrieve_courses(
        int $limit = 30,
        int $page = 1,
        string $search = '',
        string $sort = 'ASC',
    )
    {
        try {
            return CourseService::getService()->retrieveCourse(
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