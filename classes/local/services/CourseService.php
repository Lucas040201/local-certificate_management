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

namespace local_certificate_management\local\services;

use local_certificate_management\local\repositories\CourseRepository;
use local_certificate_management\local\repositories\params\RetrieveCoursesParam;

class CourseService
{
    private static ?CourseService $service = null;
    private CourseRepository $repository;

    public function __construct()
    {
        $this->repository = new CourseRepository();
    }

    public function retrieveCourse(
        string $search = '',
        string $sort = 'ASC',
        int $page = 1,
        int $limit = 30
    )
    {
        $offset = ($page - 1) * $limit;
        $param = new RetrieveCoursesParam(
            $search,
            $limit,
            $offset,
            $sort
        );

        list($courses, $count) = $this->repository->retrieveCourses($param);

        return [
            'page' => $page,
            'total' => $count,
            'courses' => $courses,
        ];
    }


    public static function getService(): CourseService
    {
        if (self::$service === null) {
            self::$service = new self();
        }

        return self::$service;
    }
}