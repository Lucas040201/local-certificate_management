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

class GradeRepository extends BaseRepository
{

    protected function setTable(): void
    {
        $this->table = 'grade_items';
    }

    public function getUserGrade(
        int $courseId,
        int $userId
    )
    {
        $sql = <<<SQL
            SELECT gi.iteminstance AS activity_id, 
                   gi.itemmodule AS activity_type, 
                   gg.finalgrade AS grade
            FROM {{$this->table}} gi
            JOIN {grade_grades} gg ON gg.itemid = gi.id
            WHERE gi.iteminstance in (SELECT q.id FROM {quiz} q WHERE q.course = :courseid)
                AND gi.itemmodule = 'quiz'
                AND gg.userid = :userid
SQL;

        $params = [
            'courseid' => $courseId,
            'userid' => $userId,
        ];

        return array_values($this->builder->get_records_sql($sql, $params));

    }

    public function getUserGradeFromCourseCompletionCriteria(
        int $courseId,
        int $userId
    )
    {
        $sql = <<<SQL
        SELECT 
            ccc.id AS criterio_id, 
            COALESCE(gg.finalgrade, 0) AS grade, 
            gg.userid
        FROM {course_completion_criteria} ccc
        JOIN {course_modules} cm ON ccc.moduleinstance = cm.instance
        JOIN {modules} m ON cm.module = m.id
        LEFT JOIN {grade_items} gi ON gi.iteminstance = cm.instance AND gi.itemmodule = m.name
        LEFT JOIN {grade_grades} gg ON gi.id = gg.itemid AND (gg.userid = :userid OR gg.userid IS NULL)
        WHERE ccc.course = :courseid
          AND ccc.criteriatype = 4;
SQL;

        $params = [
            'courseid' => $courseId,
            'userid' => $userId,
        ];

        return array_values($this->builder->get_records_sql($sql, $params));
    }

}