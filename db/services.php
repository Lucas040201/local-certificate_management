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
 * Languages configuration for the block_class_material plugin.
 *
 * @package   block_class_material
 * @copyright 2024, Lucas Mendes {@link https://www.lucasmendesdev.com.br}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


use local_certificate_management\external\course;
use local_certificate_management\external\user;

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
];

$services = [
    'local_certificate_management_web_service'  => [
        'functions' => [
            'local_certificate_management_retrieve_courses',
            'local_certificate_management_retrieve_users'
        ],
        'enabled' => 1,
        'restrictedusers' => 0,
        'shortname' => 'service_local_certificate_management',
        'downloadfiles' => 0,
        'uploadfiles' => 0
    ],
];