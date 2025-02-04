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
        $sql = "select * from {{$this->table}} WHERE format != 'site'";

        $queryParams = [];
        if (!empty($params->search)) {
            $sql .= " AND ({$this->builder->sql_like('fullname', ':search)}')} OR {$this->builder->sql_like('shortname', ':search')}";
            $sql .= "OR id = :search)";
            $queryParams['search'] = "%{$this->builder->sql_like_escape(strtolower($params->search))}%";
        }

        $sql .= "ORDER BY id {$params->sort}";

        return array_values($this->builder->get_records_sql($sql, $queryParams, $params->offset, $params->limit));
    }
}
