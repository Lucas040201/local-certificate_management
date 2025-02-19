<?php

// This file is part of the tool_certificate plugin for Moodle - http://moodle.org/
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
 * Language strings for the certificate management.
 *
 * @package    local_certificate_management
 * @copyright  2025 Lucas Mendes <lucas.mendes.dev@outlook.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Certificate Management';
$string['managecertificates'] = 'Certificate Management';
$string['courses'] = 'Courses List';
$string['search'] = 'Search:';
$string['sort'] = 'Sort:';
$string['sort_asc'] = 'ASC';
$string['sort_desc'] = 'DESC';
$string['course_table_empty'] = 'There are no courses.';
$string['course_table_not_found'] = 'No courses found with the given parameters.';
$string['course_thead_short_name'] = 'Short Name';
$string['course_thead_name'] = 'Course Name';
$string['course_thead_enrolled_users'] = 'Enrolled Users';
$string['button_see_more'] = 'See More';
$string['users'] = 'Users List';
$string['course_not_found'] = 'Course not found!';
$string['user_table_empty'] = 'There are no users enrolled in this course!';
$string['user_table_not_found'] = 'No users were found with the given parameters.';
$string['user_thead_name'] = 'Name';
$string['user_thead_email'] = 'Email';
$string['user_thead_progress'] = 'Progress';
$string['user_thead_certificate'] = 'Certificate issued';
$string['user_thead_history'] = 'Grade History';
$string['user_thead_actions'] = 'Actions';
$string['button_user_history'] = 'History';
$string['button_issue_certificate'] = 'Issue Certificate';
$string['modal_issue_certificate_title'] = 'Issue certificate';
$string['modal_issue_certificate_label'] = 'Choose the template to generate the certificate!';
$string['modal_issue_certificate_placeholder'] = 'Select';
$string['modal_certified_issued_with_success_title'] = 'Certificate issued successfully!';
$string['modal_certified_issued_with_success_body'] = 'The certificate for student {$a->name} has been issued successfully! Click <a href="{$a->certificate}" target="_blank">here<a/> to view the certificate.';
$string['modal_certified_issued_with_error_title'] = 'Error trying to issue certificate!';
$string['modal_certified_issued_with_error_body'] = 'There was an error trying to issue certificate for student {$a}!';
$string['modal_certified_issued_with_error_body_select'] = 'Please select a certificate template to proceed!';
$string['has_certificate'] = 'YES';
$string['hasnt_certificate'] = 'NO';
$string['modal_regen_certificate'] = 'Reissue Certificate';
$string['button_see_certificate'] = 'View Certificate';
$string['modal_regen_certificate_explain'] = 'This user already has a certificate. Please choose a new template and regenerate.';
$string['button_cancel'] = 'Cancel';
$string['modal_certified_issued_not_found_body'] = 'Certificate not found!';
$string['modal_issue_history_title'] = 'Issue History';
$string['generate_history'] = 'Are you sure you want to generate the grade history for user {$a}?';
$string['button_issue_history'] = 'Issue History';
$string['button_see_history'] = 'View History';
$string['modal_history_issued_with_success_title'] = 'History issued successfully!';
$string['modal_history_issued_with_success_body'] = 'History issued successfully for student {$a->name}! <a href="{$a->history}" target="_blank">here<a/> to view the History.';
$string['modal_history_issued_with_error_title'] = 'An error occurred while trying to issue the history!';
$string['modal_history_issued_with_error_body'] = 'There was an error while trying to issue the history for student {$a}';
$string['regen_history'] = 'This user already has a history. You can view or delete it!';
$string['modal_regen_history'] = 'Reissue History';
$string['history_available'] = 'Your history is available!';
$string['history_body_message'] = 'Hi {$a->name},<br /><br />Your history is available! You can find it here:
<a href="{$a->url}">My History</a>';
