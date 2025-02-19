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

use local_certificate_management\local\repositories\params\RetrieveUsersParam;

class UsersRepository extends BaseRepository
{

    protected function setTable(): void
    {
        $this->table = 'user';
    }

    public function RetrieveUsers(
        RetrieveUsersParam $params
    ): array
    {
        $sql = <<<SQL
                select 
                    mu.id,
                    concat(mu.firstname, ' ', mu.lastname) as name,
                    mu.email,
                    case when 
                        EXISTS(
                            select 1 from {tool_certificate_issues} mtci where mtci.userid = mu.id
                        ) then 1 
                        else 0 end as has_certificate
                from {{$this->table}} mu
                inner join {user_enrolments} mue on mu.id = mue.userid 
                inner join {enrol} me on mue.enrolid = me.id 
                where me.courseid = :course
SQL;


        $sqlCount = <<<SQL
                select 
                    count(mu.id)
                from {{$this->table}} mu
                inner join {user_enrolments} mue on mu.id = mue.userid 
                inner join {enrol} me on mue.enrolid = me.id 
                where me.courseid = :course
SQL;

        $queryParams = [
            'course' => $params->courseId
        ];

        if ($params->search) {
            $whereSearch = " AND (LOWER(concat(mu.firstname, ' ', mu.lastname)) LIKE :search1 OR LOWER(mu.email) LIKE :search2)";
            $sql .= $whereSearch;
            $sqlCount .= $whereSearch;
            $queryParams['search1'] = "%{$this->builder->sql_like_escape(strtolower($params->search))}%";
            $queryParams['search2'] = "%{$this->builder->sql_like_escape(strtolower($params->search))}%";
        }

        $sql .= " ORDER BY mu.id {$params->sort}";

        return [array_values($this->builder->get_records_sql($sql, $queryParams, $params->offset, $params->limit)), $this->builder->count_records_sql($sqlCount, $queryParams)];
    }

    public function getUser(int $userId)
    {
        $sql = <<<SQL
        select 
            mu.*,
            muid.data AS document
        from {{$this->table}} mu
        LEFT JOIN {user_info_data} muid ON muid.userid = mu.id
        LEFT JOIN {user_info_field} muif ON muif.id = muid.fieldid AND muif.shortname = 'cpf'
        where mu.id = :userid
SQL;

        return $this->builder->get_record_sql($sql, [
            'userid' => $userId
        ]);
    }
}
