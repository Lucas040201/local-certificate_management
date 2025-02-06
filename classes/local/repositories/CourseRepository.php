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

namespace local_certificate_management\local\repositories;

use local_certificate_management\local\repositories\params\RetrieveCoursesParam;

class CourseRepository extends BaseRepository
{
    protected function setTable(): void
    {
        $this->table = 'course';
    }


    public function RetrieveCourses(
        RetrieveCoursesParam $params
    ): array
    {
        $sqlEnrolledUsers = "(select count(*) from {user_enrolments} mue inner join {enrol} me on mue.enrolid = me.id where me.courseid = mc.id)";
        $sql = "select mc.id, mc.shortname as short_name, mc.fullname, {$sqlEnrolledUsers} as enrolled_users from {{$this->table}} as mc WHERE mc.format != 'site'";
        $sqlCount = "select count(*) from {{$this->table}} mc WHERE mc.format != 'site'";

        $queryParams = [];
        if (!is_null($params->search)) {
            $whereSearch = " AND (mc.fullname LIKE :search1 OR mc.shortname LIKE :search2)";
            $sql .= $whereSearch;
            $sqlCount .= $whereSearch;
            $queryParams['search1'] = "%{$this->builder->sql_like_escape(strtolower($params->search))}%";
            $queryParams['search2'] = "%{$this->builder->sql_like_escape(strtolower($params->search))}%";
        }

        $sql .= " ORDER BY mc.id {$params->sort}";

        return [array_values($this->builder->get_records_sql($sql, $queryParams, $params->offset, $params->limit)), $this->builder->count_records_sql($sqlCount, $queryParams)];
    }
}
