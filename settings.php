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

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/' . $CFG->admin . '/tool/certificate/adminlib.php');

if ($hassiteconfig || \tool_certificate\permission::can_view_admin_tree()) {

    $ADMIN->add('certificates', new \tool_certificate\admin_externalpage('tool_certificate/managetecertificates',
                get_string('managecertificates', 'local_certificate_management'),
                new moodle_url('/local/certificate_management/courses.php'), function() {
                    return \tool_certificate\permission::can_view_admin_tree();
                }
        ));
}
