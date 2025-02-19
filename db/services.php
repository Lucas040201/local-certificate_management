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

use local_certificate_management\external\course;
use local_certificate_management\external\user;
use local_certificate_management\external\history;
use local_certificate_management\external\certificate;
use local_certificate_management\external\template;

$functions = [
    'local_certificate_management_retrieve_courses' => [
        'classname'   => course::class,
        'methodname'  => 'retrieve_courses',
        'description' => 'Retrieve all courses with the given parameters',
        'type'        => 'read',
        'ajax'          => true,
    ],
    'local_certificate_management_retrieve_users' => [
        'classname'   => user::class,
        'methodname'  => 'retrieve_users',
        'description' => 'Retrieve all users with the given parameters',
        'type'        => 'read',
        'ajax'          => true,
    ],
    'local_certificate_management_retrieve_templates' => [
        'classname'   => template::class,
        'methodname'  => 'retrieve_templates',
        'description' => 'Retrieve all templates',
        'type'        => 'read',
        'ajax'          => true,
    ],
    'local_certificate_management_issue_certificate' => [
        'classname'   => certificate::class,
        'methodname'  => 'issue_certificate',
        'description' => 'Issue a certificate',
        'type'        => 'write',
        'ajax'          => true,
    ],
    'local_certificate_management_get_certificate_url' => [
        'classname'   => certificate::class,
        'methodname'  => 'get_certificate_url',
        'description' => 'Get Certificate Url',
        'type'        => 'read',
        'ajax'          => true,
    ],
    'local_certificate_management_get_history_certificate' => [
        'classname'   => history::class,
        'methodname'  => 'get_grade_history',
        'description' => 'Find history of grade',
        'type'        => 'read',
        'ajax'          => true,
    ],
    'local_certificate_management_issue_history_certificate' => [
        'classname'   => history::class,
        'methodname'  => 'issue_grade_history',
        'description' => 'Issue a history grade',
        'type'        => 'write',
        'ajax'          => true,
    ],
];

$services = [
    'local_certificate_management_web_service'  => [
        'functions' => [
            'local_certificate_management_retrieve_courses',
            'local_certificate_management_retrieve_users',
            'local_certificate_management_retrieve_templates',
            'local_certificate_management_issue_certificate',
            'local_certificate_management_get_certificate_url',
            'local_certificate_management_get_history_certificate',
            'local_certificate_management_issue_history_certificate',
        ],
        'enabled' => 1,
        'restrictedusers' => 0,
        'shortname' => 'service_local_certificate_management',
        'downloadfiles' => 0,
        'uploadfiles' => 0
    ],
];