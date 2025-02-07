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

use core_completion\progress;
use local_certificate_management\local\repositories\UsersRepository;
use local_certificate_management\local\repositories\params\RetrieveUsersParam;

class UsersService
{
    private static ?UsersService $service = null;
    private UsersRepository $repository;

    public function __construct()
    {
        $this->repository = new UsersRepository();
    }

    public function retrieveUsers(
        int $courseId,
        string $search = '',
        string $sort = 'ASC',
        int $page = 1,
        int $limit = 30
    )
    {
        $course = get_course($courseId);

        $offset = ($page - 1) * $limit;
        $param = new RetrieveUsersParam(
            $courseId,
            $search,
            $limit,
            $offset,
            $sort
        );

        list($users, $count) = $this->repository->retrieveUsers($param);

        return [
            'page' => $page,
            'total' => $count,
            'users' => array_map(function ($user) use ($course) {
                $progress = (int) progress::get_course_progress_percentage($course, $user->id) ?? 0;
                $user->progress = $progress . '%';
                return $user;
            }, $users),
        ];;
    }


    public static function getService(): UsersService
    {
        if (self::$service === null) {
            self::$service = new self();
        }

        return self::$service;
    }
}